<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Simple_GDPR_Cookie_Compliance
 * @subpackage Simple_GDPR_Cookie_Compliance/includes
 * @author     themebeez <themebeez@gmail.com>
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Simple_GDPR_Cookie_Compliance
 * @subpackage Simple_GDPR_Cookie_Compliance/includes
 * @author     themebeez <themebeez@gmail.com>
 */
class Simple_GDPR_Cookie_Compliance {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Simple_GDPR_Cookie_Compliance_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SIMPLE_GDPR_COOKIE_COMPLIANCE_VERSION' ) ) {
			$this->version = SIMPLE_GDPR_COOKIE_COMPLIANCE_VERSION;
		} else {
			$this->version = '1.0.1';
		}
		$this->plugin_name = 'simple-gdpr-cookie-compliance';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Simple_GDPR_Cookie_Compliance_Loader. Orchestrates the hooks of the plugin.
	 * - Simple_GDPR_Cookie_Compliance_i18n. Defines internationalization functionality.
	 * - Simple_GDPR_Cookie_Compliance_Admin. Defines all hooks for the admin area.
	 * - Simple_GDPR_Cookie_Compliance_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-simple-gdpr-cookie-compliance-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-simple-gdpr-cookie-compliance-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-simple-gdpr-cookie-compliance-admin.php';

		/**
		 * The class responsible for defining all settings in plugin page.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-simple-gdpr-cookie-compliance-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/class-simple-gdpr-cookie-compliance-public.php';

		/**
		 * Load UDP agent class.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/udp/init.php';

		$this->loader = new Simple_GDPR_Cookie_Compliance_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Simple_GDPR_Cookie_Compliance_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Simple_GDPR_Cookie_Compliance_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		global $pagenow;

		$plugin_admin = new Simple_GDPR_Cookie_Compliance_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'plugin_menu' );

		if (
			'admin.php' === $pagenow &&
			isset( $_GET['page'] ) && // phpcs:ignore
			'simple-gdpr-cookie-compliance' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) // phpcs:ignore
		) {
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		}

		// custom link in plugins.php page in wp-admin.
		$this->loader->add_filter( 'plugin_action_links_' . SIMPLE_GDPR_COOKIE_COMPLIANCE_BASENAME, $plugin_admin, 'plugin_page_links', 10, 2 );

		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'plugin_row_meta', 10, 2 );

		$plugin_options = new Simple_GDPR_Cookie_Compliance_Settings( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_init', $plugin_options, 'register_settings' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Simple_GDPR_Cookie_Compliance_Public( $this->get_plugin_name(), $this->get_version() );

		if ( ! isset( $_COOKIE['s_gdpr_c_c_cookie'] ) ) {
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_action( 'wp_footer', $plugin_public, 'display_notice' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'print_dynamic_style' );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Simple_GDPR_Cookie_Compliance_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}