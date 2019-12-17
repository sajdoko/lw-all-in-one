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
      if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        wp_send_json_error(__('Something went wrong!', $this->plugin_name));
        die();
      } elseif ($data->response == 'verified') {
        if ($data->token == '') {
          wp_send_json_error(__('WIM authorized but token returned was empty!', $this->plugin_name));
          die();
        } else if (strlen($data->token) == 32) {
          wp_send_json_success(array('token' => $data->token, 'message' => __('Web Instant Messenger authorized!', $this->plugin_name)));
          die();
        } else {
          wp_send_json_error(__('There was an unknown error!', $this->plugin_name));
          die();
        }
      } elseif ($data->response == 'unverified') {
        wp_send_json_error(__('Web Instant Messenger unauthorized!', $this->plugin_name));
        die();
      } else {
        wp_send_json_error(__('Not a valid domain!', $this->plugin_name));
        die();
      }
    } else {
      wp_send_json_error(__('Action is not valid!', $this->plugin_name));
      die();
    }
  }

  public function lw_all_in_one_insert_wim_footer() {
    //Plugin options
    $options = get_option($this->plugin_name);
    $wim_activate = (isset($options['wim_activate'])) ? $options['wim_activate'] : '';
    $wim_fields_verification_status = (isset($options['wim_fields']['verification_status'])) ? $options['wim_fields']['verification_status'] : '';
    $wim_fields_rag_soc = (isset($options['wim_fields']['rag_soc'])) ? $options['wim_fields']['rag_soc'] : '';
    if ($wim_activate === 'on' && $wim_fields_verification_status === 'verified' && $wim_fields_rag_soc !== '') {
      echo '<script type="text/javascript">
              (function(d){
                var s = d.getElementsByTagName(\'script\'),f = s[s.length-1], p = d.createElement(\'script\');
                window.WidgetId = "USC_WIDGET";
                p.type = \'text/javascript\';
                p.setAttribute(\'charset\',\'utf-8\');
                p.async = 1;
                p.id = "ultimate_support_chat";
                p.src = "//www.localweb.it/chat/widget/ultimate_chat_widget.js";
                f.parentNode.insertBefore(p, f);
              }(document));
            </script>';
      echo '<p id="rag_soc" style="display:none">';
      echo $wim_fields_rag_soc;
      echo '</p>';
    } elseif ($wim_activate !== 'on') {
      echo '<script type="text/javascript">
            console.log("'. esc_attr__('WIM not activated!', $this->plugin_name).'");
            </script>';
    } elseif ($wim_fields_verification_status !== 'verified') {
      echo '<script type="text/javascript">
            console.log("'. esc_attr__('WIM not verified!', $this->plugin_name).'");
            </script>';
    } elseif ($wim_fields_rag_soc === '') {
      echo '<script type="text/javascript">
            console.log("'. esc_attr__('Missing business name!', $this->plugin_name).'");
            </script>';
    } else {
      echo '<script type="text/javascript">
            console.log("'. esc_attr__('WIM installed!', $this->plugin_name).'");
            </script>';
    }
  }

  public function lw_all_in_one_old_wim_is_active_deactivate() {
    if (is_plugin_active('web-instant-messenger/web-instant-messenger.php')) {
			delete_option( 'wim_activation_status' );
			delete_option( 'web-instant-messenger' );
      deactivate_plugins('web-instant-messenger/web-instant-messenger.php');
      delete_plugins(array('web-instant-messenger/web-instant-messenger.php'));
    } elseif (is_plugin_inactive('web-instant-messenger/web-instant-messenger.php')) {
			delete_option( 'wim_activation_status' );
			delete_option( 'web-instant-messenger' );
      delete_plugins(array('web-instant-messenger/web-instant-messenger.php'));
    }
  }

}
