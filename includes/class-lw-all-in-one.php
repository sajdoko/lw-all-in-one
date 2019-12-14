<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/includes
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
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/includes
 * @author     sajdoko <sajmir.doko@localweb.it>
 */
class Lw_All_In_One {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Lw_All_In_One_Loader    $loader    Maintains and registers all hooks for the plugin.
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
        if (defined('LW_ALL_IN_ONE_VERSION')) {
            $this->version = LW_ALL_IN_ONE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'lw_all_in_one';

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
     * - Lw_All_In_One_Loader. Orchestrates the hooks of the plugin.
     * - Lw_All_In_One_i18n. Defines internationalization functionality.
     * - Lw_All_In_One_Admin. Defines all hooks for the admin area.
     * - Lw_All_In_One_Public. Defines all hooks for the public side of the site.
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
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-lw-all-in-one-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-lw-all-in-one-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-lw-all-in-one-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-lw-all-in-one-public.php';

        $this->loader = new Lw_All_In_One_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Lw_All_In_One_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Lw_All_In_One_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Lw_All_In_One_Admin($this->get_plugin_name(), $this->get_version());

		// Frontend Hooks
        $this->loader->add_action( 'wp_head', $plugin_admin, 'lw_all_in_one_header_scripts');

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // Add menu item
        $this->loader->add_action('admin_menu', $plugin_admin, 'lw_all_in_one_add_admin_menu', 99);

        // Save/Update our plugin options
        $this->loader->add_action('admin_init', $plugin_admin, 'lw_all_in_one_options_update');

        // Add Settings link to the plugin
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . 'lw-all-in-one.php');
        $this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'lw_all_in_one_add_action_links');

        // WooCommerce Google Analytics Integration Admin Notice
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'woocommerce_google_analytics_missing_notice');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Lw_All_In_One_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        //Plugin options
        $options = get_option($this->plugin_name);
        $ga_activate = (isset($options['ga_activate'])) ? $options['ga_activate'] : '';
        $ga_fields_tracking_id = (isset($options['ga_fields']['tracking_id'])) ? $options['ga_fields']['tracking_id'] : '';
        if ($ga_activate === 'on' && $ga_fields_tracking_id !== '') {
            $this->loader->add_action( 'wp_ajax_nopriv_lw_all_in_one_save_ga_event', $plugin_public, 'lw_all_in_one_save_ga_event' );
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
     * @return    Lw_All_In_One_Loader    Orchestrates the hooks of the plugin.
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
