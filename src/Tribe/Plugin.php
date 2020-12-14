<?php
namespace Tribe\Extensions\Summary_View;

/**
 * Class Plugin
 *
 * @since   1.0.0
 *
 * @package Tribe\Extensions\Summary_View
 */
class Plugin extends \tad_DI52_ServiceProvider {
	/**
	 * Stores the version for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Stores the base slug for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const SLUG = 'summary-view';

	/**
	 * Stores the view slug for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const VIEW_SLUG = 'summary';

	/**
	 * Stores the base slug for the extension.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const FILE = TRIBE_EXTENSION_COMPACT_VIEW_FILE;

	/**
	 * @since 1.0.0
	 *
	 * @var string Plugin Directory.
	 */
	public $plugin_dir;

	/**
	 * @since 1.0.0
	 *
	 * @var string Plugin path.
	 */
	public $plugin_path;

	/**
	 * @since 1.0.0
	 *
	 * @var string Plugin URL.
	 */
	public $plugin_url;

	/**
	 * Where in the themes we will look for templates
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $template_namespace = 'events';

	/**
	 * Setup the Extension's properties.
	 *
	 * This always executes even if the required plugins are not present.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		// Set up the plugin provider properties.
		$this->plugin_path = trailingslashit( dirname( static::FILE ) );
		$this->plugin_dir  = trailingslashit( basename( $this->plugin_path ) );
		$this->plugin_url  = plugins_url( $this->plugin_dir, $this->plugin_path );

		// Register this provider as the main one and use a bunch of aliases.
		$this->container->singleton( static::class, $this );
		$this->container->singleton( 'extension.summary_view', $this );
		$this->container->singleton( 'extension.summary_view.plugin', $this );
		//$this->container->register( PUE::class );

		if ( ! $this->check_plugin_dependencies() ) {
			// If the plugin dependency manifest is not met, then bail and stop here.
			return;
		}

		// Start binds.



		// End binds.

		include_once $this->plugin_path . 'src/functions/general.php';

		tribe_register_provider( '\Tribe\Extensions\Summary_View\Rewrite\Provider' );

		$hooks = new Hooks( $this->container );
		$hooks->register();

		$this->container->singleton( Hooks::class, $hooks );

		$assets = new Assets( $this->container );
		$assets->register();
		$this->container->singleton( Assets::class, $assets );
	}

	/**
	 * Checks whether the plugin dependency manifest is satisfied or not.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Whether the plugin dependency manifest is satisfied or not.
	 */
	protected function check_plugin_dependencies() {
		$this->register_plugin_dependencies();

		return tribe_check_plugin( static::class );
	}

	/**
	 * Registers the plugin and dependency manifest among those managed by Tribe Common.
	 *
	 * @since 1.0.0
	 */
	protected function register_plugin_dependencies() {
		$plugin_register = new Plugin_Register();
		$plugin_register->register_plugin();

		$this->container->singleton( Plugin_Register::class, $plugin_register );
		$this->container->singleton( 'extension.summary_view', $plugin_register );
	}
}
