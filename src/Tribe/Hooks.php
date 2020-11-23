<?php
/**
 * Handles hooking all the actions and filters used by the module.
 *
 * To remove a filter:
 * ```php
 *  remove_filter( 'some_filter', [ tribe( Tribe\Extensions\Summary_View\Hooks::class ), 'some_filtering_method' ] );
 *  remove_filter( 'some_filter', [ tribe( 'extension.summary_view.hooks' ), 'some_filtering_method' ] );
 * ```
 *
 * To remove an action:
 * ```php
 *  remove_action( 'some_action', [ tribe( Tribe\Extensions\Summary_View\Hooks::class ), 'some_method' ] );
 *  remove_action( 'some_action', [ tribe( 'extension.summary_view.hooks' ), 'some_method' ] );
 * ```
 *
 * @since   1.0.0
 *
 * @package Tribe\Extensions\Summary_View;
 */

namespace Tribe\Extensions\Summary_View;

use Tribe\Extensions\Summary_View\Views\Summary_View as View;
use Tribe__Main as Common;
use Tribe__Template;

/**
 * Class Hooks.
 *
 * @since   1.0.0
 *
 * @package Tribe\Extensions\Summary_View;
 */
class Hooks extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->container->singleton( static::class, $this );
		$this->container->singleton( 'extension.summary_view.hooks', $this );

		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Adds the actions required by the plugin.
	 *
	 * @since 1.0.0
	 */
	protected function add_actions() {
		add_action( 'tribe_load_text_domains', [ $this, 'load_text_domains' ] );
		add_action( 'tribe_events_pre_rewrite', [ $this, 'filter_add_routes' ], 5 );
	}

	/**
	 * Adds the filters required by the plugin.
	 *
	 * @since 1.0.0
	 */
	protected function add_filters() {
		add_filter( 'tribe_events_views', [ $this, 'filter_events_views' ] );
		add_filter( 'tribe-events-bar-views', [ $this, 'setup_summary_view_in_bar' ], 30, 1 );
		add_filter( 'tribe_template_path_list', [ $this, 'filter_template_path_list' ], 15, 2 );
	}

	/**
	 * Load text domain for localization of the plugin.
	 *
	 * @since 1.0.0
	 */
	public function load_text_domains() {
		$mopath = tribe( Plugin::class )->plugin_dir . 'lang/';
		$domain = '__TRIBE_DOMAIN__';

		// This will load `wp-content/languages/plugins` files first.
		Common::instance()->load_text_domain( $domain, $mopath );
	}

	/**
	 * Filters the available Views to add the ones implemented in PRO.
	 *
	 * @since 1.0.0
	 *
	 * @param array $views An array of available Views.
	 *
	 * @return array The array of available views, including the PRO ones.
	 */
	public function filter_events_views( array $views = [] ) {
		$views['summary'] = View::class;

		return $views;
	}

	/**
	 * Add photo view to the views selector in the tribe events bar.
	 *
	 * @since 1.0.0
	 *
	 * @param array $views The current array of views registered to the tribe bar.
	 *
	 * @return array The views registered with photo view added.
	 */
	public function setup_summary_view_in_bar( $views ) {
		$views[] = array(
			'displaying'     => 'summary',
			'anchor'         => __( 'Summary', 'tribe-events-calendar-pro' ),
			'event_bar_hook' => 'tribe_events_before_template',
			'url'            => \tribe_get_summary_permalink(),
		);

		return $views;
	}

	/**
	 * Filters the list of folders TEC will look up to find templates to add the ones defined by tribe-ext-summary-view.
	 *
	 * @since 1.0.0
	 *
	 * @param array           $folders  The current list of folders that will be searched template files.
	 * @param Tribe__Template $template Which template instance we are dealing with.
	 *
	 * @return array The filtered list of folders that will be searched for the templates.
	 */
	public function filter_template_path_list( array $folders = [], Tribe__Template $template ) {
		$main = tribe( Plugin::class );

		$path = (array) rtrim( $main->plugin_path, '/' );

		// Pick up if the folder needs to be added to the public template path.
		$folder = $template->get_template_folder();

		if ( ! empty( $folder ) ) {
			$path = array_merge( $path, $folder );
		}

		$folders[ Plugin::SLUG ] = [
			'id'        => Plugin::SLUG,
			'namespace' => $main->template_namespace,
			'priority'  => 25,
			'path'      => implode( DIRECTORY_SEPARATOR, $path ),
		];

		return $folders;
	}
}
