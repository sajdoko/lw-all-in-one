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
    if (preg_match('/page_lw_all_in_one/', $hook)) {
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
    if (preg_match('/page_lw_all_in_one/', $hook)) {
      wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/lw-all-in-one-admin.js', array('jquery'), $this->version, false);
      wp_localize_script($this->plugin_name, 'lw_all_in_one_admin_ajax_object',
        array(
          'ajaxurl' => admin_url('admin-ajax.php'),
          'security' => wp_create_nonce($this->plugin_name),
          // 'data_var_1' => 'value 1',
          // 'data_var_2' => 'value 2',
        )
      );
    }
  }

  public function lw_all_in_one_add_admin_menu() {
    /*
     * Add a settings page for this plugin to the Settings menu.
     *
     */
    add_menu_page(__('LocalWeb All In One Options', LW_ALL_IN_ONE_PLUGIN_NAME), __('LW AIO Options', LW_ALL_IN_ONE_PLUGIN_NAME), 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'), plugin_dir_url(__FILE__) . '/img/icon.png', 81
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
      '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', LW_ALL_IN_ONE_PLUGIN_NAME) . '</a>',
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
      $valid['ga_fields']['save_ga_events'] = $this->get_plugin_options('ga_fields', 'save_ga_events');
      $valid['ga_fields']['monitor_email_link'] = $this->get_plugin_options('ga_fields', 'monitor_email_link');
      $valid['ga_fields']['monitor_tel_link'] = $this->get_plugin_options('ga_fields', 'monitor_tel_link');
      $valid['ga_fields']['monitor_form_submit'] = $this->get_plugin_options('ga_fields', 'monitor_form_submit');
      add_settings_error(
        $this->plugin_name,
        $this->plugin_name . '_ga_fields_tracking_id_not_valid',
        __('Tracking ID is NOT valid!', LW_ALL_IN_ONE_PLUGIN_NAME),
        'error'
      );
    } else {
      $valid['ga_fields']['save_ga_events'] = (isset($input['ga_fields']['save_ga_events']) && $input['ga_fields']['save_ga_events'] === 'on') ? 'on' : '';
      $valid['ga_fields']['monitor_email_link'] = (isset($input['ga_fields']['monitor_email_link']) && $input['ga_fields']['monitor_email_link'] === 'on') ? 'on' : '';
      $valid['ga_fields']['monitor_tel_link'] = (isset($input['ga_fields']['monitor_tel_link']) && $input['ga_fields']['monitor_tel_link'] === 'on') ? 'on' : '';
      $valid['ga_fields']['monitor_form_submit'] = (isset($input['ga_fields']['monitor_form_submit']) && $input['ga_fields']['monitor_form_submit'] === 'on') ? 'on' : '';
    }

    $valid['wim_activate'] = (isset($input['wim_activate']) && $input['wim_activate'] === 'on') ? 'on' : '';
    if (isset($input['wim_fields']['verification_status']) && isset($input['wim_fields']['save_wim_options']) && isset($input['wim_fields']['token']) && strlen($input['wim_fields']['token']) == 32) {
      $wim_settings_arr = array();
      $api_url = 'https://localweb.it/chat/api/cliente/aggiorna.php';
      $wim_settings_arr['wim_fields']['verification_status'] = (isset($input['wim_fields']['verification_status'])) ? sanitize_text_field($input['wim_fields']['verification_status']) : "";
      $wim_settings_arr['wim_fields']['token'] = (isset($input['wim_fields']['token'])) ? sanitize_text_field($input['wim_fields']['token']) : "";
      $wim_settings_arr['wim_fields']['rag_soc'] = (isset($input['wim_fields']['rag_soc'])) ? sanitize_text_field($input['wim_fields']['rag_soc']) : "";
      $wim_settings_arr['wim_fields']['auto_show_wim'] = (isset($input['wim_fields']['auto_show_wim'])) ? sanitize_text_field($input['wim_fields']['auto_show_wim']) : "SI";
      $wim_settings_arr['wim_fields']['show_wim_after'] = (isset($input['wim_fields']['show_wim_after'])) ? sanitize_text_field($input['wim_fields']['show_wim_after']) : "5";
      $wim_settings_arr['wim_fields']['show_mobile'] = (isset($input['wim_fields']['show_mobile'])) ? sanitize_text_field($input['wim_fields']['show_mobile']) : "SI";
      $wim_settings_arr['wim_fields']['lingua'] = (isset($input['wim_fields']['lingua'])) ? sanitize_text_field($input['wim_fields']['lingua']) : "it";
      $wim_settings_arr['wim_fields']['messaggio_0'] = (isset($input['wim_fields']['messaggio_0'])) ? sanitize_textarea_field($input['wim_fields']['messaggio_0']) : "Salve! Come posso esserle utile?";
      $wim_settings_arr['wim_fields']['messaggio_1'] = (isset($input['wim_fields']['messaggio_1'])) ? sanitize_textarea_field($input['wim_fields']['messaggio_1']) : "Gentilmente, mi può lasciare un contatto telefonico o email in modo da poterla eventualmente ricontattare?";
      $response = wp_remote_post($api_url, array(
          'method' => 'POST',
          'timeout' => 45,
          'redirection' => 5,
          'httpversion' => '1.0',
          'blocking' => true,
          'headers' => array(),
          'body' => json_encode(array('plugin_token' => $wim_settings_arr['wim_fields']['token'], 'auto_show_wim' => $wim_settings_arr['wim_fields']['auto_show_wim'], 'show_wim_after' => $wim_settings_arr['wim_fields']['show_wim_after'], 'show_mobile' => $wim_settings_arr['wim_fields']['show_mobile'], 'lingua' => $wim_settings_arr['wim_fields']['lingua'], 'messaggio_0' => $wim_settings_arr['wim_fields']['messaggio_0'], 'messaggio_1' => $wim_settings_arr['wim_fields']['messaggio_1'])),
          'cookies' => array(),
      )
      );
      $ret_body = wp_remote_retrieve_body($response);
      $data = json_decode($ret_body);
      if (is_wp_error($response)) {
          $error_message = $response->get_error_message();
          add_settings_error(
              $this->plugin_name,
              $this->plugin_name . '_settings_not_updated_error',
              _e('Something went wrong: ' . $error_message, LW_ALL_IN_ONE_PLUGIN_NAME),
              'error'
          );
      } elseif ($data->response == 'success') {
        $valid = array_merge($valid, $wim_settings_arr);
      } elseif ($data->response == 'danger') {
          add_settings_error(
              $this->plugin_name,
              $this->plugin_name . '_settings_not_updated_danger',
              $data->message,
              'error'
          );
      } else {
          add_settings_error(
              $this->plugin_name,
              $this->plugin_name . '_settings_not_updated_not_known',
              $data->message,
              'error'
          );
      }
    }

    $valid['cf7_activate'] = (isset($input['cf7_activate']) && $input['cf7_activate'] === 'on') ? 'on' : '';
    $valid['lw_cf7_fields']['save_cf7_subm'] = (isset($input['lw_cf7_fields']['save_cf7_subm']) && $input['lw_cf7_fields']['save_cf7_subm'] === 'on') ? 'on' : '';
    if ($valid['cf7_activate'] !== '' && !is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
      $valid['cf7_activate'] = '';
      $valid['lw_cf7_fields']['save_cf7_subm'] = '';
      add_settings_error(
        $this->plugin_name,
        $this->plugin_name . '_lw_cf7_main_not_active',
        __('Contact Form 7 plugin is not active!', LW_ALL_IN_ONE_PLUGIN_NAME),
        'error'
      );
    }
    $valid['lw_hf_fields']['insert_header'] = (isset($input['lw_hf_fields']['insert_header'])) ? $this->sanitize_header_footer_scripts($input['lw_hf_fields']['insert_header']) : '';
    $valid['lw_hf_fields']['insert_footer'] = (isset($input['lw_hf_fields']['insert_footer'])) ? $this->sanitize_header_footer_scripts($input['lw_hf_fields']['insert_footer']) : '';

    $exiting_options = get_option($this->plugin_name);
    if ($exiting_options) {
      $valid = array_merge($exiting_options, $valid);
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

  public function lw_all_in_one_header_scripts_from_tab() {
    //Plugin options
    $options = get_option($this->plugin_name);
    $lw_hf_fields_insert_header = (isset($options['lw_hf_fields']['insert_header'])) ? $options['lw_hf_fields']['insert_header'] : '';

    if ($lw_hf_fields_insert_header !== '') {
      echo (base64_decode($lw_hf_fields_insert_header)), "\n";
    }
  }

  public function lw_all_in_one_footer_scripts_from_tab() {
    //Plugin options
    $options = get_option($this->plugin_name);
    $lw_hf_fields_insert_footer = (isset($options['lw_hf_fields']['insert_footer'])) ? $options['lw_hf_fields']['insert_footer'] : '';

    if ($lw_hf_fields_insert_footer !== '') {
      echo (base64_decode($lw_hf_fields_insert_footer)), "\n";
    }
  }

  public function sanitize_header_footer_scripts($scripts) {
    // Remove PHP code
    $scripts = preg_replace('/<\?php.+?\?>$/ms', '', $scripts);
    return base64_encode($scripts);
  }

}
