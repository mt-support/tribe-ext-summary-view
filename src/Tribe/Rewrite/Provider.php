<?php
/**
 * Provides the rewrite rules suppor for the Compact View.
 *
 * @since   1.0.0
 * @package Tribe\Events\Pro\Rewrite
 */

namespace Tribe\Extensions\Compact_View\Rewrite;

use Tribe\Extensions\Compact_View\Plugin;

/**
 * Class Provider
 *
 * @since   1.0.0
 * @package Tribe\Extensions\Compact_View\Rewrite
 */
class Provider extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 1.0.0
	 */
	public function register()
	{
		$this->container->singleton( Rewrite::class, Rewrite::class );
		$this->add_filters();
	}

	/**
	 * Adds the filter required to provide the rewrite support.
	 *
	 * @since 4.7.5
	 */
	protected function add_filters() {
		add_action( 'tribe_events_pre_rewrite', [ $this, 'filter_add_routes' ], 5 );
		add_filter( 'tribe_events_rewrite_base_slugs', [ $this, 'filter_add_base_slugs' ], 11 );
		add_filter( 'tribe_events_rewrite_matchers_to_query_vars_map', [ $this, 'filter_add_matchers_to_query_vars_map' ], 11, 2 );
	}

	/**
	 * Add rewrite routes for custom PRO stuff and views.
	 *
	 * @since 4.7.5 Moved here from Main file.
	 *
	 * @param \Tribe__Events__Rewrite $rewrite The Tribe__Events__Rewrite object
	 *
	 * @return void
	 */
	public function filter_add_routes( $rewrite ) {
		$rewrite
			->archive( [ '{{ ' . Plugin::VIEW_SLUG . ' }}' ], [ 'eventDisplay' => Plugin::VIEW_SLUG ] )
			->archive( [ '{{ ' . Plugin::VIEW_SLUG . ' }}', '{{ featured }}' ], [ 'eventDisplay' => Plugin::VIEW_SLUG, 'featured' => true ] )
			->archive( [ '{{ ' . Plugin::VIEW_SLUG . ' }}', '(\d{4}-\d{2}-\d{2})' ], [ 'eventDisplay' => Plugin::VIEW_SLUG, 'eventDate' => '%1' ] )
			->archive( [ '{{ ' . Plugin::VIEW_SLUG . ' }}', '(\d{4}-\d{2}-\d{2})', '{{ featured }}' ], [
				'eventDisplay' => Plugin::VIEW_SLUG,
				'eventDate'    => '%1',
				'featured'     => true
			] )
			->tax( [ '{{ ' . Plugin::VIEW_SLUG . ' }}' ], [ 'eventDisplay' => Plugin::VIEW_SLUG ] )
			->tax( [ '{{ ' . Plugin::VIEW_SLUG . ' }}', '{{ featured }}' ], [ 'eventDisplay' => Plugin::VIEW_SLUG, 'featured' => true ] )
			->tag( [ '{{ ' . Plugin::VIEW_SLUG . ' }}' ], [ 'eventDisplay' => Plugin::VIEW_SLUG ] )
			->tag( [ '{{ ' . Plugin::VIEW_SLUG . ' }}', '{{ featured }}' ], [ 'eventDisplay' => Plugin::VIEW_SLUG, 'featured' => true ] );
	}

	/**
	 * Add the required bases for the Pro Views
	 *
	 * @since 4.7.5 Moved here from Main file.
	 *
	 * @param array $bases Bases that are already set
	 *
	 * @return array         The modified version of the array of bases
	 */
	public function filter_add_base_slugs( $bases = [] ) {
		// Support the original and translated forms for added robustness
		$bases[ Plugin::VIEW_SLUG ] = [  Plugin::VIEW_SLUG , Plugin::VIEW_SLUG ];

		return $bases;
	}

	/**
	 * Add the required bases for the Compact View.
	 *
	 * @since 1.0.0
	 *
	 * @param array $bases Bases that are already set.
	 *
	 * @return array         The modified version of the array of bases.
	 */
	public function filter_add_matchers_to_query_vars_map( $matchers = [], $rewrite = null ) {

		$matchers[ Plugin::VIEW_SLUG ] = 'eventDisplay';

		return $matchers;
	}
}
