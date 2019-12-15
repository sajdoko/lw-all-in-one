<?php

/**
 * Creates LocalWeb Privacy&Policy pages.
 *
 * @link       https://localweb.it/
 * @since      1.0.0
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 */

/**
 * Creates LocalWeb Privacy&Policy pages.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 * @author     sajdoko <sajmir.doko@localweb.it>
 */
class Lw_All_In_One_Privacy_Policy_Pages {

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

  public function lw_all_in_one_privacy_policy_admin_menu() {
    add_submenu_page($this->plugin_name, __('LocalWeb Privacy&Policy Pages Options', $this->plugin_name), __('Privacy&Policy Pages', $this->plugin_name), 'manage_options', $this->plugin_name . '_privacy_policy', array($this, 'lw_all_in_one_privacy_policy_display_page'));
  }

  public function lw_all_in_one_privacy_policy_display_page() {
    include_once 'partials/lw-all-in-one-admin-privacy-policy-display.php';
  }
  public function localize_script() {
    wp_localize_script($this->plugin_name, 'lw_all_in_one_create_privacy_pages_object',
    array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'security' => wp_create_nonce($this->plugin_name),
      // 'data_var_1' => 'value 1',
      // 'data_var_2' => 'value 2',
    )
  );
  }

  public function lw_all_in_one_create_privacy_pages() {
    if (!check_ajax_referer($this->plugin_name, 'security')) {
      wp_send_json_error(__('Security is not valid!', $this->plugin_name));
      die();
    }
    wp_send_json_success(__('Event Saved!', $this->plugin_name));
    die();
  }

}
