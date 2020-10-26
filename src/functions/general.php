<?php

if ( ! function_exists( 'tribe_get_compact_permalink' ) ) {
	/**
	 * Get the compact permalink.
	 *
	 * @param bool|int|null $term
	 *
	 * @return string $permalink
	 */
	function tribe_get_compact_permalink( $term = null ) {
		$permalink = Tribe__Events__Main::instance()->getLink( 'compact', null, $term );

		/**
		 * Provides an opportunity to modify the compact view permalink.
		 *
		 * @var string $permalink
		 * @var mixed  $term
		 */
		return apply_filters( 'tribe_get_compact_view_permalink', $permalink, $term );
	}
}
