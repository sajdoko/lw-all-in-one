<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://localweb.it/
 * @since      1.0.0
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 * @author     sajdoko <sajmir.doko@localweb.it>
 */
class Lw_All_In_One_Admin {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles($hook) {
    if ($hook == 'toplevel_page_lw_all_in_one' || $hook == 'lw-aio-options_page_lw_all_in_one_ga_events' || $hook == 'lw-aio-options_page_lw_all_in_one_cf7') {
      wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/lw-all-in-one-admin.css', array(), $this->version, 'all');
    }
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts($hook) {
    // echo $hook;
    // die();
    if ($hook == 'toplevel_page_lw_all_in_one' || $hook == 'lw-aio-options_page_lw_all_in_one_ga_events' || $hook == 'lw-aio-options_page_lw_all_in_one_cf7') {
      wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/lw-all-in-one-admin.js', array('jquery'), $this->version, false);
    }
  }

  public function lw_all_in_one_add_admin_menu() {
    /*
     * Add a settings page for this plugin to the Settings menu.
     *
     */
    add_menu_page(__('LocalWeb All In One Options', $this->plugin_name), __('LW AIO Options', $this->plugin_name), 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'), plugin_dir_url(__FILE__) . '/img/icon.png', 81
    );
  }

  /**
   * Render the settings page for this plugin.
   *
   * @since    1.0.0
   */

  public function display_plugin_setup_page() {
    include_once 'partials/lw-all-in-one-admin-display.php';
  }

  /**
   * Add's action links to the plugins page.
   *
   * @since    1.0.0
   */

  public function lw_all_in_one_add_action_links($links) {
    $settings_link = array(
      '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
    );
    return array_merge($settings_link, $links);
  }

  /**
   * Sanitize plugin input options.
   *
   * @since    1.0.0
   */
  public function validate_lw_all_in_one_settings($input) {
    $valid = array();
    $valid['ga_activate'] = (isset($input['ga_activate']) && $input['ga_activate'] === 'on') ? 'on' : '';
    $valid['ga_fields']['tracking_id'] = (isset($input['ga_fields']['tracking_id'])) ? $input['ga_fields']['tracking_id'] : '';
    if ($valid['ga_fields']['tracking_id'] !== '' && !$this->lw_all_in_one_validate_tracking_id($valid['ga_fields']['tracking_id'])) {
      $valid['ga_fields']['tracking_id'] = $this->get_plugin_options('ga_fields', 'tracking_id');
      $valid['ga_fields']['monitor_email_link'] = $this->get_plugin_options('ga_fields', 'monitor_email_link');
      $valid['ga_fields']['monitor_tel_link'] = $this->get_plugin_options('ga_fields', 'monitor_tel_link');
      $valid['ga_fields']['monitor_form_submit'] = $this->get_plugin_options('ga_fields', 'monitor_form_submit');
      add_settings_error(
        $this->plugin_name,
        $this->plugin_name . '_ga_fields_tracking_id_not_valid',
        __('Tracking ID is NOT valid!', $this->plugin_name),
        'error'
      );
    } else {
      $valid['ga_fields']['monitor_email_link'] = (isset($input['ga_fields']['monitor_email_link']) && $input['ga_fields']['monitor_email_link'] === 'on') ? 'on' : '';
      $valid['ga_fields']['monitor_tel_link'] = (isset($input['ga_fields']['monitor_tel_link']) && $input['ga_fields']['monitor_tel_link'] === 'on') ? 'on' : '';
      $valid['ga_fields']['monitor_form_submit'] = (isset($input['ga_fields']['monitor_form_submit']) && $input['ga_fields']['monitor_form_submit'] === 'on') ? 'on' : '';
    }
    $valid['wim_activate'] = (isset($input['wim_activate']) && $input['wim_activate'] === 'on') ? 'on' : '';
    $valid['lw_cf7'] = (isset($input['lw_cf7']) && $input['lw_cf7'] === 'on') ? 'on' : '';
    if ($valid['lw_cf7'] !== '' && !is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
      $valid['lw_cf7'] = '';
      add_settings_error(
        $this->plugin_name,
        $this->plugin_name . '_lw_cf7_main_not_active',
        __('Contact Form 7 plugin is not active!', $this->plugin_name),
        'error'
      );
    }

    return $valid;
  }

  /**
   * Register plugin input options.
   *
   * @since    1.0.0
   */
  public function lw_all_in_one_options_update() {
    register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate_lw_all_in_one_settings'));
  }

  /**
   * Check if plugin option exists and returns it's vale, else return empty.
   *
   * @since     1.0.0
   */
  public function get_plugin_options($parent_key = false, $key) {
    $options = get_option($this->plugin_name);
    if ($parent_key !== false) {
      if (isset($options[$parent_key][$key])) {
        return $options[$parent_key][$key];
      } else {
        return '';
      }
    } else {
      if (isset($options[$key])) {
        return $options[$key];
      } else {
        return '';
      }
    }
  }

  /**
   * Regular Expression snippet to validate Google Analytics tracking code
   * see http://code.google.com/apis/analytics/docs/concepts/gaConceptsAccounts.html#webProperty
   *
   * @param   $str     string to be validated
   * @return  Boolean
   * @since    1.0.0
   */
  public function lw_all_in_one_validate_tracking_id($str) {
    return preg_match('/^ua-\d{4,9}-\d{1,4}$/i', strval($str)) ? true : false;
  }

}
