<?php

/**
 * The Contact Form 7 integration functionality of the plugin.
 *
 * @link       https://localweb.it/
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 */

/**
 * The Contact Form 7 integration functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 * @author     sajdoko <sajmir.doko@localweb.it>
 */
class Lw_All_In_One_Cf7 {

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
    $this->lw_api_url = "https://localwebapi.ids.al/contactFormWeb";

  }

  public function lw_all_in_one_cf7_admin_submenu() {
    add_submenu_page($this->plugin_name, __('Saved Contact Form Submissions', LW_ALL_IN_ONE_PLUGIN_NAME), __('Saved CF7', LW_ALL_IN_ONE_PLUGIN_NAME), 'manage_options', $this->plugin_name . '_cf7', array($this, 'lw_all_in_one_cf7_display_page'));
  }

  public function lw_all_in_one_cf7_display_page() {
    include_once 'partials/lw-all-in-one-admin-cf7-display.php';
  }

  public function lw_all_in_one_cf7_to_db($WPCF7_ContactForm) {

    $url_path = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    $domain = htmlspecialchars($url_path, ENT_QUOTES, 'UTF-8');
    $domain = trim($domain, '/');
    if (!preg_match('#^http(s)?://#', $domain)) {
      $domain = 'http://' . $domain;
    }
    $url_parts = parse_url($domain);
    $submited_page = preg_replace('/^www\./', '', $url_parts['host']);

    $submission = WPCF7_Submission::get_instance();
    $posted_data = &$submission->get_posted_data();

    if (isset($posted_data['nome'])) {
      $mapped_field = array();
      $mapped_field['nome'] = $posted_data['nome'];
      $mapped_field['cognome'] = $posted_data['cognome'];
      $mapped_field['email'] = $posted_data['email'];
      $mapped_field['telefono'] = $posted_data['telefono'];
      $mapped_field['oggetto'] = $posted_data['oggetto'];
      $mapped_field['messaggio'] = $posted_data['messaggio'];
      $mapped_field['tipo_Contratto'] = $posted_data['tipo-contratto'];
      $mapped_field['id_Contratto'] = $posted_data['id-contratto'];
      $mapped_field['submited_page'] = $submited_page;

      $json_mapped_fields = json_encode($mapped_field);

      $args = array(
        'body' => $json_mapped_fields,
        'timeout' => '5',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array(),
      );

      $send = wp_remote_post($this->lw_api_url, $args);
      $ret_body = wp_remote_retrieve_body($send);
      $data = json_decode($ret_body);
      if (is_wp_error($ret_body)) {
        $sent = "No";
      } elseif ($data->response == "OK") {
        $sent = "Si";
      } else {
        $sent = "No";
      }

      global $wpdb;
      $name = $mapped_field['nome'];
      $surname = $mapped_field['cognome'];
      $email = $mapped_field['email'];
      $phone = $mapped_field['telefono'];
      $subject = $mapped_field['oggetto'];
      $message = $mapped_field['messaggio'];
      $tipo_Contratto = $mapped_field['tipo_Contratto'];
      $id_Contratto = $mapped_field['id_Contratto'];
      $time = time();
      //Plugin options
      $options = get_option($this->plugin_name);
      $lw_cf7_fields_saved_cf7_subm = (isset($options['lw_cf7_fields']['save_cf7_subm'])) ? $options['lw_cf7_fields']['save_cf7_subm'] : '';
      if ($lw_cf7_fields_saved_cf7_subm === 'on') {
        $cf7_table = $wpdb->prefix . LW_ALL_IN_ONE_CF7_TABLE;
        $wpdb->insert(
          $cf7_table,
          array(
            'time' => $time,
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'tipo_Contratto' => $tipo_Contratto,
            'id_Contratto' => $id_Contratto,
            'submited_page' => $submited_page,
            'sent' => $sent,
          )
        );
      }
    }
  }
  public function lw_all_in_one_cf7_add_every_five_minutes($schedules) {
    $schedules['lw_all_in_one_cf7_every_five_minutes'] = array(
      'interval' => 300,
      'display' => __('Every 5 Minutes', 'localweb'),
    );
    return $schedules;
  }

  public function lw_all_in_one_cf7_sent_activation() {
    if (!wp_next_scheduled('lw_all_in_one_cf7_check_sent_data')) {
      wp_schedule_event(time(), 'lw_all_in_one_cf7_every_five_minutes', 'lw_all_in_one_cf7_check_sent_data');
    }
  }

  public function lw_all_in_one_cf7_sent_deactivation() {
    wp_clear_scheduled_hook('lw_all_in_one_cf7_check_sent_data');
  }

  public function lw_all_in_one_cf7_every_5_minutes() {
    global $wpdb;
    $cf7_table = $wpdb->prefix . LW_ALL_IN_ONE_CF7_TABLE;
    $select_nn_inviato = $wpdb->get_row("SELECT * FROM " . $cf7_table . " WHERE sent !='Si'");
    if ($select_nn_inviato !== null) {
      $re_invia = array();
      $re_invia['nome'] = $select_nn_inviato->name;
      $re_invia['cognome'] = $select_nn_inviato->surname;
      $re_invia['email'] = $select_nn_inviato->email;
      $re_invia['telefono'] = $select_nn_inviato->phone;
      $re_invia['soggetto'] = $select_nn_inviato->subject;
      $re_invia['messaggio'] = $select_nn_inviato->message;
      $re_invia['tipo_Contratto'] = $select_nn_inviato->tipo_Contratto;
      $re_invia['id_Contratto'] = $select_nn_inviato->id_Contratto;
      $re_invia['submited_page'] = $select_nn_inviato->submited_page;

      $json_re_invia = json_encode($re_invia);
      $args = array(
        'body' => $json_re_invia,
        'timeout' => '5',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array(),
      );
      $send = wp_remote_post($this->lw_api_url, $args);
      $ret_body = wp_remote_retrieve_body($send);
      $data = json_decode($ret_body);

      if ($data->response == "OK") {
        $inviato = "Si";
        $id = $select_nn_inviato->id;
        $wpdb->update(
          $cf7_table,
          array(
            'sent' => $inviato,
          ), array('id' => $id)
        );
      }
    }
  }

  public function lw_all_in_one_old_cf7_is_active_deactivate() {
    if (is_plugin_active('lw-contact-form/localweb.php')) {
      deactivate_plugins('lw-contact-form/localweb.php');
      delete_plugins(array('lw-contact-form/localweb.php'));
    } elseif (is_plugin_inactive('lw-contact-form/localweb.php')) {
      delete_plugins(array('lw-contact-form/localweb.php'));
    }
  }

}
