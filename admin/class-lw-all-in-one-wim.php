<?php

/**
 * The Web Instant Messenger functionality of the plugin.
 *
 * @link       https://localweb.it/
 * @since      1.0.0
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 */

/**
 * The Web Instant Messenger functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 * @author     sajdoko <sajmir.doko@localweb.it>
 */
class Lw_All_In_One_Wim {

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
    $this->wim_veify_api_url = "https://localweb.it/chat/api/cliente/verifica-plugin.php";
    $this->wim_update_api_url = "https://localweb.it/chat/api/cliente/aggiorna.php";

  }

  public function lw_all_in_one_verify_wim_attivation() {
    if (!check_ajax_referer($this->plugin_name, 'security')) {
      wp_send_json_error(__('Security is not valid!', $this->plugin_name));
      die();
    }
    if (isset($_POST['action']) && $_POST['action'] === "lw_all_in_one_verify_wim_attivation") {
      $domain = get_option('siteurl', $_SERVER['HTTP_HOST']);
      $response = wp_remote_get($this->wim_veify_api_url, array(
        'method' => 'GET',
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => array('domain' => urlencode($domain)),
        'cookies' => array(),
      )
      );
      $ret_body = wp_remote_retrieve_body($response);
      $data = json_decode($ret_body);
      $option_activation_status = array();
      if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        wp_send_json_error(__('Something went wrong!', $this->plugin_name));
        die();
      } elseif ($data->response == 'verified') {
        $option_activation_status['wim_fields']['verification_status'] = 1;
        $option_activation_status['wim_fields']['token'] = ($data->token != '') ? $data->token : '';
        $exiting_options = get_option($this->plugin_name);
        if ($exiting_options) {
          $option_activation_status = array_merge($exiting_options, $option_activation_status);
        }
        update_option($this->plugin_name, $option_activation_status);
        wp_send_json_success(__('Web Instant Messenger authorized!', $this->plugin_name));
        die();
      } elseif ($data->response == 'unverified') {
        wp_send_json_error(__('Web Instant Messenger unauthorized!', $this->plugin_name));
        die();
      } else {
        $option_activation_status['wim_fields']['verification_status'] = 1;
        $option_activation_status['wim_fields']['token'] = ($data->token != '') ? $data->token : '';
        $exiting_options = get_option($this->plugin_name);
        if ($exiting_options) {
          $option_activation_status = array_merge($exiting_options, $option_activation_status);
        }
        update_option($this->plugin_name, $option_activation_status);
        wp_send_json_error(__('Not a valid domain!', $this->plugin_name));
        die();
      }
    } else {
      wp_send_json_error(__('Action is not valid!', $this->plugin_name));
      die();
    }
  }

  // public function lw_all_in_one_ga_events_admin_menu() {
  //   add_submenu_page($this->plugin_name, __('Saved Google Analytics Events', $this->plugin_name), __('Saved GA Events', $this->plugin_name), 'manage_options', $this->plugin_name . '_ga_events', array($this, 'lw_all_in_one_ga_events_display_page'));
  // }

  // public function lw_all_in_one_ga_events_display_page() {
  //   include_once 'partials/lw-all-in-one-admin-ga-events-display.php';
  // }

}
