<?php
/**
 * The Summary View.
 *
 * @package Tribe\Extensions\Summary_View\Views\Summary_View
 * @since 1.0.0
 */

namespace Tribe\Extensions\Summary_View\Views;

use Tribe\Events\Views\V2\Views\List_View;
use Tribe__Date_Utils as Dates;

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
	 * Overrides the base View method to fix the order of the events in the `past` display mode.
	 *
	 * @since 1.0.0
	 *
	 * @return array The List View template vars, modified if required.
	 */
	protected function setup_template_vars() {
		$template_vars = parent::setup_template_vars();

		$is_past        = 'past' === $this->context->get( 'event_display_mode' );
		$request_date   = $template_vars['request_date'];
		$events_by_date = [];

		foreach ( $template_vars['events'] as $event ) {
			$display_date = empty( $is_past ) && ! empty( $request_date )
				? max( $event->dates->start_display, $request_date )
				: $event->dates->start_display;

			$event_date_attr = $display_date->format( Dates::DBDATEFORMAT );

			if ( ! isset( $events_by_date[ $event_date_attr ] ) ) {
				$events_by_date[ $event_date_attr ] = [];
			}

			$events_by_date[ $event_date_attr ][] = $event;
		}

		$template_vars['events_by_date'] = $events_by_date;

		return $template_vars;

		// NEW ATTEMPT
		// @todo: @borkweb - Finish this to replace the above
		$template_vars = parent::setup_template_vars();

		$is_past        = 'past' === $this->context->get( 'event_display_mode' );
		$request_date   = $template_vars['request_date'];
		$events_by_date = [];

		$dates_to_inject = [];

		foreach ( $template_vars['events'] as $event ) {
			$start_date = empty( $is_past ) && ! empty( $request_date )
				? max( $event->dates->start_display, $request_date )
				: $event->dates->start_display;

			$date = $start_date->format( Dates::DBDATEFORMAT );

			if ( ! isset( $events_by_date[ $date ] ) ) {
				$events_by_date[ $date ] = [];
			}

			$events_by_date[ $date ][] = $event;
		}

		$days = array_keys( $events_by_date );

		if ( count( $days ) > 1 ) {
			$first_date = Dates::build_date_object( array_shift( $days ) );
			$next_date  = $first_date->add( new \DateInterval( 'P1D' ) );
			$last_date  = Dates::build_date_object( array_pop( $days ) );

			$overlapping_events = tribe_events()
				->where( 'ends_between', tribe_beginning_of_day( $next_date->format( Dates::DBDATEFORMAT ) ), tribe_end_of_day( $last_date->format( Dates::DBDATEFORMAT ) ) )
				->all();
		} else {
			$first_date = Dates::build_date_object( array_shift( $days ) );

			$overlapping_events = tribe_events()
				->where( 'starts_before', tribe_beginning_of_day( $first_date->format( Dates::DBDATEFORMAT ) ) )
				->where( 'ends_between', tribe_beginning_of_day( $first_date->format( Dates::DBDATEFORMAT ) ), tribe_end_of_day( $first_date->format( Dates::DBDATEFORMAT ) ) )
				->all();
		}

		$overlapping_by_date = [];

		foreach ( $overlapping_events as $event ) {
			$start_date = empty( $is_past ) && ! empty( $request_date )
				? max( $event->dates->start_display, $request_date )
				: $event->dates->start_display;

			$end_date = empty( $is_past ) && ! empty( $request_date )
				? max( $event->dates->end_display, $request_date )
				: $event->dates->end_display;

			$date = $start_date->format( Dates::DBDATEFORMAT );

			if ( ! isset( $overlapping_by_date[ $date ] ) ) {
				$overlapping_by_date[ $date ] = [];
			}

			$overlapping_by_date[ $date ][] = $event;

			$diff = $start_date->diff( $end_date )->format( '%a' );

			$event_increment = $start_date;
			for ( $i = 1; $i < $diff; $i++ ) {
				$date_increment = $event_increment->add( new \DateInterval( 'P' . $i . 'D' ) )->format( Dates::DBDATEFORMAT );
				if ( ! isset( $dates_to_inject[ $date_increment ] ) ) {
					$dates_to_inject[ $date_increment ] = [];
				}

				$dates_to_inject[ $date_increment ][] = $event;
			}
		}

		$i = 1;

		foreach ( $dates_to_inject as $date => $events ) {
			if ( ! isset( $events_by_date[ $date ] ) ) {
				continue;
			}

			$events_by_date[ $date ] = array_merge( $events, $events_by_date[ $date ] );
		}

		$template_vars['events_by_date'] = $events_by_date;

		return $template_vars;
	}
}
