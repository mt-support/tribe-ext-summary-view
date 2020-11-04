<?php
/**
 * The Compact View.
 *
 * @package Tribe\Extensions\Compact_View\Views\Compact_View
 * @since 1.0.0
 */

namespace Tribe\Extensions\Compact_View\Views;

use Tribe\Events\Views\V2\Views\List_View;
use Tribe__Date_Utils as Dates;

class Compact_View extends List_View {

	/**
	 * Slug for this view
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $slug = 'compact';

	/**
	 * Overrides the base View method to fix the order of the events in the `past` display mode.
	 *
	 * @since 4.9.11
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
	}
}
