<?php
/**
 * The Summary View.
 *
 * @package Tribe\Extensions\Summary_View\Views\Summary_View
 * @since 1.0.0
 */

namespace Tribe\Extensions\Summary_View\Views;

use Tribe__Context as Context;
use Tribe__Date_Utils as Dates;
use Tribe\Events\Views\V2\Views\List_View;
use Tribe\Utils\Date_I18n;
use Tribe\Utils\Date_I18n_Immutable;

class Summary_View extends List_View {

	/**
	 * Slug for this view
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $slug = 'summary';

	/**
	 * Events per page
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	protected $events_per_page = 25;

	protected $date_group_order_tracking = [];

	/**
	 * {@inheritDoc}
	 */
	protected function setup_repository_args( Context $context = null ) {
		$context = null !== $context ? $context : $this->context;

		/**
		 * Filters the events_per_page for the summary view.
		 *
		 * @since 1.0.0
		 *
		 * @param int $events_per_page Events per page.
		 * @param Context $context Request context.
		 */
		$summary_view_events_per_page = apply_filters( 'tec_events_summary_view_events_per_page', $this->events_per_page, $context );

		$context = $context->alter( [
			'events_per_page' => $summary_view_events_per_page,
		] );

		$args = parent::setup_repository_args( $context );

		return $args;
	}

	/**
	 * Overrides the base View method to fix the order of the events in the `past` display mode.
	 *
	 * @since 1.0.0
	 *
	 * @return array The List View template vars, modified if required.
	 */
	protected function setup_template_vars() {
		$template_vars     = parent::setup_template_vars();
		$is_past           = 'past' === $this->context->get( 'event_display_mode' );
		$request_date      = $template_vars['request_date'];
		$events_by_date    = [];
		$month_transition  = [];
		$injectable_events = [];
		$current_month     = null;
		$earliest_event    = current( $template_vars['events'] );
		$ids               = wp_list_pluck( $template_vars['events'], 'ID' );
		$previous_event    = $this->get_previous_event( $earliest_event, $ids );

		foreach ( $template_vars['events'] as $event ) {
			$start_date = empty( $is_past ) && ! empty( $request_date )
				? max( $event->dates->start_display, $request_date )
				: $event->dates->start_display;

			$end_date = $event->dates->end_display;

			$group_date      = tribe_beginning_of_day( $start_date->format( Dates::DBDATEFORMAT ), Dates::DBDATEFORMAT );
			$formatted_month = substr( $group_date, 0, 7 );

			$event = $this->add_view_specific_properties_to_event( $event, $group_date );

			// When we transition to a new month, store the date of transition.
			if ( empty( $current_month ) || $formatted_month !== $current_month ) {
				$current_month                   = $formatted_month;
				$month_transition[ $group_date ] = $group_date;
			}

			if ( ! isset( $events_by_date[ $group_date ] ) ) {
				$events_by_date[ $group_date ] = [];
			}

			$events_by_date[ $group_date ][ $event->dates->start->format( Dates::DBDATETIMEFORMAT ) . ' - ' . $event->ID ] = $event;

			$injectable_events = $this->maybe_extend_event_to_other_dates( $event, $start_date, $end_date, $injectable_events );
		}

		if ( ! empty( $previous_event ) ) {
			$injectable_events = $this->maybe_include_overlapping_events( $events_by_date, $previous_event, $injectable_events );
		}

		$events_by_date = $this->inject_events_into_result_dates( $injectable_events, $events_by_date );

		// Ensure event dates are sorted in ascending order.
		ksort( $events_by_date );

		$template_vars['events_by_date']   = $events_by_date;
		$template_vars['month_transition'] = $month_transition;

		return $template_vars;
	}

	protected function add_view_specific_properties_to_event( $event ) {
		$start_date = tribe_beginning_of_day( $event->dates->start->format( Dates::DBDATEFORMAT ), Dates::DBDATEFORMAT );
		$end_date   = tribe_beginning_of_day( $event->dates->end->format( Dates::DBDATEFORMAT ), Dates::DBDATEFORMAT );

		$formatted_start_date_beginning = tribe_beginning_of_day( $event->dates->start->format( Dates::DBDATEFORMAT ), tribe_get_option( 'dateWithoutYearFormat' ) );
		$formatted_end_date_ending      = tribe_beginning_of_day( $event->dates->end->format( Dates::DBDATEFORMAT ), tribe_get_option( 'dateWithoutYearFormat' ) );

		$event->summary_view = (object) [
			'start_time'           => $event->dates->start->format( 'g:i a'),
			'end_time'             => $event->dates->end->format( 'g:i a'),
			'start_date'           => $start_date,
			'end_date'             => $end_date,
			'formatted_start_date' => $formatted_start_date_beginning,
			'formatted_end_date'   => $formatted_end_date_ending,
		];

		return $event;
	}

	/**
	 * Gets events that start before and end during the event result set.
	 *
	 * @since 1.0.0
	 *
	 * @param Date_I18n|Date_I18n_Immutable $first_date First date in the result set.
	 * @param Date_I18n|Date_I18n_Immutable $last_date Last date in the result set.
	 *
	 * @return array
	 */
	protected function get_events_that_start_before_and_end_between( $first_date, $last_date ) {
		$first_date_beginning_of_day = tribe_beginning_of_day( $first_date->format( Dates::DBDATEFORMAT ) );
		$last_date_end_of_day        = tribe_end_of_day( $last_date->format( Dates::DBDATEFORMAT ) );

		$events = tribe_events()
			->where( 'starts_before', $first_date_beginning_of_day )
			->where( 'ends_between', $first_date_beginning_of_day, $last_date_end_of_day );

		return $events->all();
	}

	/**
	 * Gets events that start and end around the event result set.
	 *
	 * @since 1.0.0
	 *
	 * @param Date_I18n|Date_I18n_Immutable $first_date First date in the result set.
	 * @param Date_I18n|Date_I18n_Immutable $last_date Last date in the result set.
	 *
	 * @return array
	 */
	protected function get_events_that_start_and_end_around( $first_date, $last_date ) {
		$first_date_beginning_of_day = tribe_beginning_of_day( $first_date->format( Dates::DBDATEFORMAT ) );
		$last_date_beginning_of_day  = tribe_beginning_of_day( $last_date->format( Dates::DBDATEFORMAT ) );

		$events = tribe_events()
			->where( 'starts_before', $first_date_beginning_of_day )
			->where( 'ends_after', $last_date_beginning_of_day );

		return $events->all();
	}

	/**
	 * Gets the most recent event backward in time.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post $earliest_event First event in result set.
	 * @param array $exclude_ids Event IDs in result set.
	 *
	 * @return \WP_Post
	 */
	protected function get_previous_event( \WP_Post $earliest_event, array $exclude_ids = [] ) {
		return tribe_events()
			->where( 'starts_before', $earliest_event->dates->start )
			->not_in( $exclude_ids )
			->per_page( 1 )
			->page( 1 )
			->order( 'DESC' )
			->first();
	}

	/**
	 * Take an event and extend it within a date array to each date that it occurs on.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post            $event Event post.
	 * @param Date_I18n|Date_I18n_Immutable $start_date Start date of the event.
	 * @param Date_I18n|Date_I18n_Immutable $end_date End date of the event.
	 * @param array               $dates Array of dates to organize events (by reference).
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	protected function maybe_extend_event_to_other_dates( \WP_Post $event, $start_date, $end_date, array $dates ) {
		$start_date_beginning = Dates::build_date_object( tribe_beginning_of_day( $start_date->format( Dates::DBDATETIMEFORMAT ) ) );
		$end_date_beginning   = Dates::build_date_object( tribe_beginning_of_day( $end_date->format( Dates::DBDATETIMEFORMAT ) ) );

		// Calculate the difference in days between the start and end of the event.
		$diff = $start_date_beginning->diff( $end_date_beginning )->format( '%a' );

		for ( $i = 1; $i <= $diff; $i++ ) {
			// Don't modify the $start_date in the loop!
			$start = Dates::build_date_object( $start_date );
			$date = tribe_beginning_of_day( $start->add( new \DateInterval( 'P' . $i . 'D' ) )->format( Dates::DBDATEFORMAT ), Dates::DBDATEFORMAT );

			if ( empty( $dates[ $date ] ) ) {
				$dates[ $date ] = [];
			}

			if ( ! isset( $this->date_group_order_tracking[ $date ] ) ) {
				$this->date_group_order_tracking[ $date ] = 1;
			} else {
				$this->date_group_order_tracking[ $date ]++;
			}

			$dates[ $date ][ $event->dates->start->format( Dates::DBDATEFORMAT ) . ' 00:00:00 - ' . str_pad( $this->date_group_order_tracking[ $date ], 3, '0', STR_PAD_LEFT ) ] = $event;
		}

		return $dates;
	}

	/**
	 * Gets the first and last dates included in the result set.
	 *
	 * @since 1.0.0
	 *
	 * @param array $event_dates Associative array indexed by date of events in the initial result set.
	 *
	 * @return array
	 */
	protected function get_first_and_last_dates_in_the_result_set( array $event_dates ) {
		$dates_in_result_set = array_keys( $event_dates );

		if ( count( $dates_in_result_set ) > 1 ) {
			$first_date = Dates::build_date_object( array_shift( $dates_in_result_set ) );
			$last_date  = Dates::build_date_object( array_pop( $dates_in_result_set ) );
		} else {
			$first_date = Dates::build_date_object( array_shift( $dates_in_result_set ) );
			$last_date  = $first_date;
		}

		return [ $first_date, $last_date ];
	}

	/**
	 * Include any events that start before events in the result set that overlap with the result set.
	 *
	 * @since 1.0.0
	 *
	 * @param array $events_by_date Nested array of events in result set indexed by date.
	 * @param \WP_Post $previous_event Event right before the view's result set.
	 * @param array $injectable_events Nested array of injectable events indexed by date.
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	protected function maybe_include_overlapping_events( array $events_by_date, \WP_Post $previous_event, array $injectable_events ) {
		list( $first_date, $last_date ) = $this->get_first_and_last_dates_in_the_result_set( $events_by_date );

		$previous_event_date                  = $previous_event->dates->start;
		$first_date_beginning_of_day          = tribe_beginning_of_day( $first_date->format( Dates::DBDATEFORMAT ), 'Y-m-d' );
		$previous_event_date_beginning_of_day = tribe_beginning_of_day( $previous_event_date->format( Dates::DBDATEFORMAT ), 'Y-m-d' );

		$overlapping_events = [];

		if ( $previous_event_date_beginning_of_day !== $first_date_beginning_of_day ) {
			$start_before         = $this->get_events_that_start_before_and_end_between( $first_date, $last_date );
			$start_and_end_around = $this->get_events_that_start_and_end_around( $first_date, $last_date );
			$overlapping_events   = array_merge( $start_before, $start_and_end_around );
		}

		foreach ( $overlapping_events as $event ) {
			$start_date           = $event->dates->start_display;
			$end_date             = $event->dates->end_display;
			$formatted_start_date = tribe_beginning_of_day( $start_date->format( Dates::DBDATEFORMAT ), 'Y-m-d' );

			if ( ! isset( $injectable_events[ $formatted_start_date ] ) ) {
				$injectable_events[ $formatted_start_date ] = [];
			}

			$injectable_events = $this->maybe_extend_event_to_other_dates( $event, $start_date, $end_date, $injectable_events );
		}

		return $injectable_events;
	}

	/**
	 * Merge injectable events into the array of dates returned by the result set.
	 *
	 * Any injectable event that occurs on a date that does not appear in the result set will be excluded.
	 *
	 * @since 1.0.0
	 *
	 * @param array $injectable_events Nested array of events that can be injected indexed by date.
	 * @param array $events_by_date Nested array of events in result set indexed by date.
	 *
	 * @return array
	 */
	protected function inject_events_into_result_dates( array $injectable_events, array $events_by_date ) {
		foreach ( $injectable_events as $date => $events ) {
			if ( ! isset( $events_by_date[ $date ] ) ) {
				continue;
			}

			$events_by_date[ $date ] = array_merge( $events, $events_by_date[ $date ] );
			ksort( $events_by_date[ $date ] );
		}

		return $events_by_date;
	}
}
