<?php

/**
 * The Web Instant Messenger functionality of the plugin.
 *
 * @link       https://localweb.it/
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 */

/**
 * The Web Instant Messenger functionality of the plugin.
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 * @author     sajdoko <sajmir.doko@localweb.it>
 */
class Lw_All_In_One_Wim {

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
    $this->wim_veify_api_url = "https://localweb.it/chat/api/cliente/verifica-plugin.php";

  }

  public function lw_all_in_one_verify_wim_attivation() {
    if (!current_user_can('manage_options')) {
      return;
    }
    if (!check_ajax_referer($this->plugin_name, 'security')) {
      wp_send_json_error(__('Security is not valid!', LW_ALL_IN_ONE_PLUGIN_NAME));
      die();
    }
    if (isset($_POST['action']) && $_POST['action'] === "lw_all_in_one_verify_wim_attivation") {
      $domain = clean_domain(get_option('siteurl', $_SERVER['HTTP_HOST']));
      $response = wp_remote_get($this->wim_veify_api_url, array(
        'method' => 'GET',
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => array('domain' => urlencode($domain), 'action' => 'get_options'),
        'cookies' => array(),
      )
      );
      $ret_body = wp_remote_retrieve_body($response);
      $data = recursive_sanitize_array_object(json_decode($ret_body));
      if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        wp_send_json_error(__('Something went wrong!', LW_ALL_IN_ONE_PLUGIN_NAME));
        die();
      } elseif ($data->response == 'verified') {
        if ($data->token == '') {
          wp_send_json_error(__('WIM authorized but token returned was empty!', LW_ALL_IN_ONE_PLUGIN_NAME));
          die();
        } else if (strlen($data->token) == 32) {
          wp_send_json_success(array('fields' => $data, 'message' => __('Web Instant Messenger authorized!', LW_ALL_IN_ONE_PLUGIN_NAME)));
          die();
        } else {
          wp_send_json_error(__('There was an unknown error!', LW_ALL_IN_ONE_PLUGIN_NAME));
          die();
        }
      } elseif ($data->response == 'unverified') {
        wp_send_json_error($data->message);
        die();
      } else {
        wp_send_json_error(__('Not a valid response!', LW_ALL_IN_ONE_PLUGIN_NAME));
        die();
      }
    } else {
      wp_send_json_error(__('Action is not valid!', LW_ALL_IN_ONE_PLUGIN_NAME));
      die();
    }
  }

  public function lw_all_in_one_insert_wim_footer() {
    //Plugin options
    $options = get_option($this->plugin_name);
    $wim_activate = (isset($options['wim_activate'])) ? sanitize_text_field($options['wim_activate']) : '';
    $wim_fields_verification_status = (isset($options['wim_fields']['verification_status'])) ? sanitize_text_field($options['wim_fields']['verification_status']) : '';
    $wim_fields_rag_soc = (isset($options['wim_fields']['rag_soc'])) ? sanitize_text_field($options['wim_fields']['rag_soc']) : '';
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
      echo esc_js($wim_fields_rag_soc);
      echo '</p>';
    } elseif ($wim_activate !== 'on') {
      echo '<script type="text/javascript">
            console.log("' . esc_attr__('WIM not activated!', LW_ALL_IN_ONE_PLUGIN_NAME) . '");
            </script>';
    } elseif ($wim_fields_verification_status !== 'verified') {
      echo '<script type="text/javascript">
            console.log("' . esc_attr__('WIM not verified!', LW_ALL_IN_ONE_PLUGIN_NAME) . '");
            </script>';
    } elseif ($wim_fields_rag_soc === '') {
      echo '<script type="text/javascript">
            console.log("' . esc_attr__('Missing business name!', LW_ALL_IN_ONE_PLUGIN_NAME) . '");
            </script>';
    } else {
      echo '<script type="text/javascript">
            console.log("' . esc_attr__('WIM installed!', LW_ALL_IN_ONE_PLUGIN_NAME) . '");
            </script>';
    }
  }

  public function lw_all_in_one_old_wim_is_active_deactivate() {
    if (is_plugin_active('web-instant-messenger/web-instant-messenger.php')) {
      delete_option('wim_activation_status');
      delete_option('web-instant-messenger');
      deactivate_plugins('web-instant-messenger/web-instant-messenger.php');
    } elseif (is_plugin_inactive('web-instant-messenger/web-instant-messenger.php')) {
      delete_option('wim_activation_status');
      delete_option('web-instant-messenger');
      delete_plugins(array('web-instant-messenger/web-instant-messenger.php'));
    }
  }

}
