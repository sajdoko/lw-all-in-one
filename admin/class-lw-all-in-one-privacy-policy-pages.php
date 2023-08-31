<?php

/**
 * Creates LocalWeb Privacy&Policy pages.
 *
 * @link       https://localweb.it/
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 */

/**
 * Creates LocalWeb Privacy&Policy pages.
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 * @author     sajdoko <sajmir.doko@localweb.it>
 */
class Lw_All_In_One_Privacy_Policy_Pages {

  /**
   * The ID of this plugin.
   *
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  public function lw_all_in_one_privacy_policy_admin_menu() {
    add_submenu_page($this->plugin_name, __('LocalWeb Privacy&Policy Pages Options', LW_ALL_IN_ONE_PLUGIN_NAME), __('Privacy&Policy Pages', LW_ALL_IN_ONE_PLUGIN_NAME), 'manage_options', LW_ALL_IN_ONE_PLUGIN_NAME . '_privacy_policy', array($this, 'lw_all_in_one_privacy_policy_display_page'));
  }

  public function lw_all_in_one_privacy_policy_display_page() {
    include_once 'partials/lw-all-in-one-admin-privacy-policy-display.php';
  }

  public function lw_all_in_one_old_privacy_is_active_deactivate() {
    if (is_plugin_active('lw-cookie-privacy/lw-cookie-privacy.php')) {
      deactivate_plugins('lw-cookie-privacy/lw-cookie-privacy.php');
    } elseif (is_plugin_inactive('lw-cookie-privacy/lw-cookie-privacy.php')) {
      delete_plugins(array('lw-cookie-privacy/lw-cookie-privacy.php'));
    }
  }

  public function lw_all_in_one_create_privacy_pages() {
    if (!check_ajax_referer($this->plugin_name, 'security')) {
      wp_send_json_error(__('Security is not valid!', LW_ALL_IN_ONE_PLUGIN_NAME));
      die();
    }
    if (isset($_POST['action']) && $_POST['action'] === "lw_all_in_one_create_privacy_pages") {

      $create_cookie_page = isset($_POST[ $this->plugin_name . '_cookie_page']) && $_POST[ $this->plugin_name . '_cookie_page'] == 'on';
      $create_privacy_page = isset($_POST[ $this->plugin_name . '_privacy_page']) && $_POST[ $this->plugin_name . '_privacy_page'] == 'on';
      $create_info_dati_page = isset($_POST[ $this->plugin_name . '_trattamento_dati_page']) && $_POST[ $this->plugin_name . '_trattamento_dati_page'] == 'on';

      $date = date('d-m-Y', time());
      $domain = get_option('siteurl', $_SERVER['HTTP_HOST']);
      $create_pages_resposes = array();
      $created_pages_save_option = array();

      if ($create_cookie_page) {
        include_once 'privacy-pages/cookie.php';
        if ($create_pages_resposes['cookie-policy']['status'] == 'success') {
          $created_pages_save_option['cookie-policy'] = array(
            'action' => $create_pages_resposes['cookie-policy']['action'],
            'post_id' => $create_pages_resposes['cookie-policy']['post_id'],
          );
        }
      }
      if ($create_privacy_page) {
        include_once 'privacy-pages/privacy.php';
        if ($create_pages_resposes['informativa-sul-trattamento-dei-dati-personali']['status'] == 'success') {
          $created_pages_save_option['informativa-sul-trattamento-dei-dati-personali'] = array(
            'action' => $create_pages_resposes['informativa-sul-trattamento-dei-dati-personali']['action'],
            'post_id' => $create_pages_resposes['informativa-sul-trattamento-dei-dati-personali']['post_id'],
          );
        }
      }
      if ($create_info_dati_page) {
        include_once 'privacy-pages/contact.php';
        if ($create_pages_resposes['informativa-trattamento-dati']['status'] == 'success') {
          $created_pages_save_option['informativa-trattamento-dati'] = array(
            'action' => $create_pages_resposes['informativa-trattamento-dati']['action'],
            'post_id' => $create_pages_resposes['informativa-trattamento-dati']['post_id'],
          );
        }
      }
      $exiting_option = get_option($this->plugin_name . '_privacy_pages');
      if ($exiting_option) {
        $created_pages_save_option = array_merge($exiting_option, $created_pages_save_option);
      }
      update_option($this->plugin_name . '_privacy_pages', $created_pages_save_option);
      wp_send_json_success($create_pages_resposes);
    } else {
      wp_send_json_error(__('Action is not valid!', LW_ALL_IN_ONE_PLUGIN_NAME));
    }
	  die();
  }

}
