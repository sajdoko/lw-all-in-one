<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://localweb.it/
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
    if (defined('LW_ALL_IN_ONE_PLUGIN_NAME')) {
      $this->plugin_name = LW_ALL_IN_ONE_PLUGIN_NAME;
    } else {
      $this->plugin_name = 'lw_all_in_one';
    }

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();
    if ($this->check_plugin_options(false, 'ga_activate') === 'on') {
      $this->define_ga_events_hooks();
    }
    if ($this->check_plugin_options(false, 'wim_activate') === 'on') {
      $this->define_wim_hooks();
    }
    if ($this->check_plugin_options(false, 'cf7_activate') === 'on') {
      $this->define_cf7_hooks();
    }
    $this->define_privacy_policy_hooks();

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
   * - Lw_All_In_One_Ga_Events. Defines all hooks for the Google Analytics integration.
   * - Lw_All_In_One_Wim. Defines all hooks for the Web Instant Messenger integration.
   * - Lw_All_In_One_Cf7. Defines all hooks for the Contact Form 7 integration.
   * - Lw_All_In_One_Privacy_Policy_Pages. Defines all hooks for the LocalWeb Privacy&Policy pages.
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

    /**
     * The class responsible for Google Analytics integration functionality.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-lw-all-in-one-ga-events.php';

    /**
     * The class responsible for Web Instant Messenger integration functionality.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-lw-all-in-one-wim.php';

    /**
     * The class responsible for Contact Form 7 integration functionality.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-lw-all-in-one-cf7.php';

    /**
     * The class responsible for LocalWeb Privacy&Policy pages functionality.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-lw-all-in-one-privacy-policy-pages.php';

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

    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

    // Add menu item
    $this->loader->add_action('admin_menu', $plugin_admin, 'lw_all_in_one_add_admin_menu', 99);

    // Save/Update our plugin options
    $this->loader->add_action('admin_init', $plugin_admin, 'lw_all_in_one_options_update');

    // Add Settings link to the plugin
    $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . 'lw-all-in-one.php');
    $this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'lw_all_in_one_add_action_links');
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

    if ($this->check_plugin_options(false, 'ga_activate') === 'on' && $this->check_plugin_options('ga_fields', 'tracking_id') !== '') {
      $this->loader->add_action('wp_ajax_lw_all_in_one_save_ga_event', $plugin_public, 'lw_all_in_one_save_ga_event');
      $this->loader->add_action('wp_ajax_nopriv_lw_all_in_one_save_ga_event', $plugin_public, 'lw_all_in_one_save_ga_event');
    }
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_ga_events_hooks() {

    $plugin_ga_events = new Lw_All_In_One_Ga_Events($this->get_plugin_name(), $this->get_version());

    // Add submenu item
    $this->loader->add_action('admin_menu', $plugin_ga_events, 'lw_all_in_one_ga_events_admin_menu', 99);

    // Frontend Hooks
    $this->loader->add_action('wp_head', $plugin_ga_events, 'lw_all_in_one_header_scripts');

    // WooCommerce Google Analytics Integration Admin Notice
    $this->loader->add_action('admin_notices', $plugin_ga_events, 'woocommerce_google_analytics_missing_notice');

    // Check if Google Analytics Dashboard for WP (GADWP) plugin is active
    $this->loader->add_action('admin_init', $plugin_ga_events, 'lw_all_in_one_gadwp_is_active_deactivate');
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_wim_hooks() {

    $plugin_wim = new Lw_All_In_One_Wim($this->get_plugin_name(), $this->get_version());

    // // Frontend Hooks
    // $this->loader->add_action('wp_head', $plugin_wim, 'lw_all_in_one_header_scripts');
		// Frontend Hooks
		$this->loader->add_action( 'wp_footer', $plugin_wim, 'lw_all_in_one_insert_wim_footer');

    $this->loader->add_action('wp_ajax_lw_all_in_one_verify_wim_attivation', $plugin_wim, 'lw_all_in_one_verify_wim_attivation');

    // Check if Web Instant Messenger plugin is active
    $this->loader->add_action('admin_init', $plugin_wim, 'lw_all_in_one_old_wim_is_active_deactivate');
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_cf7_hooks() {

    $plugin_cf7 = new Lw_All_In_One_Cf7($this->get_plugin_name(), $this->get_version());

    // Add submenu item
    $this->loader->add_action('admin_menu', $plugin_cf7, 'lw_all_in_one_cf7_admin_submenu', 99);

    // Save Contact Form 7 submmisions
    $this->loader->add_action('wpcf7_before_send_mail', $plugin_cf7, 'lw_all_in_one_cf7_to_db');
    $this->loader->add_filter('cron_schedules', $plugin_cf7, 'lw_all_in_one_cf7_add_every_five_minutes');
    $this->loader->add_action('lw_all_in_one_cf7_check_sent_data', $plugin_cf7, 'lw_all_in_one_cf7_every_5_minutes');

    // Check if LW Contact Form 7 Addon plugin exist
    $this->loader->add_action('admin_init', $plugin_cf7, 'lw_all_in_one_old_cf7_is_active_deactivate');
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_privacy_policy_hooks() {

    $plugin_privacy_policy = new Lw_All_In_One_Privacy_Policy_Pages($this->get_plugin_name(), $this->get_version());
    $this->loader->add_action('wp_ajax_lw_all_in_one_create_privacy_pages', $plugin_privacy_policy, 'lw_all_in_one_create_privacy_pages');

    // Add submenu item
    $this->loader->add_action('admin_menu', $plugin_privacy_policy, 'lw_all_in_one_privacy_policy_admin_menu', 99);

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

  /**
   * Check if plugin option exists and returns it's vale, else return false.
   *
   * @since     1.0.0
   */
  public function check_plugin_options($parent_key = false, $key) {
    $options = get_option($this->plugin_name);
    if ($parent_key !== false) {
      if (isset($options[$parent_key][$key])) {
        return $options[$parent_key][$key];
      } else {
        return false;
      }
    } else {
      if (isset($options[$key])) {
        return $options[$key];
      } else {
        return false;
      }
    }
  }

}
