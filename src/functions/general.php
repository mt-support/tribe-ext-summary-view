<?php

if ( ! function_exists( 'tribe_get_summary_permalink' ) ) {
	/**
	 * Get the summary permalink.
	 *
	 * @param bool|int|null $term
	 *
	 * @return string $permalink
	 */
	function tribe_get_summary_permalink( $term = null ) {
		$permalink = Tribe__Events__Main::instance()->getLink( 'summary', null, $term );

		/**
		 * Provides an opportunity to modify the summary view permalink.
		 *
		 * @var string $permalink
		 * @var mixed  $term
		 */
		return apply_filters( 'tribe_get_summary_view_permalink', $permalink, $term );
	}
}
