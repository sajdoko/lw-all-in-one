<?php

/**
 * Fired during plugin activation
 *
 * @link       https://localweb.it/
 * @since      1.0.0
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/includes
 * @author     sajdoko <sajmir.doko@localweb.it>
 */
class Lw_All_In_One_Activator {

  /**
   * Short Description. (use period)
   *
   * Long Description.
   *
   * @since    1.0.0
   */
  public static function activate() {
    // $version = get_option('lw_all_in_one_version', LW_ALL_IN_ONE_VERSION);
    if (!get_option('lw_all_in_one_version')) {
      add_option('lw_all_in_one_version', LW_ALL_IN_ONE_VERSION);
    }
    // Check if Web Instant Messenger options exist
    $verification_status = $token = $wim_activate = $rag_soc = $auto_show_wim = $show_wim_after = $show_mobile = $lingua = $messaggio_0 = $messaggio_1 = $cf7_activate = $save_cf7_subm = $ga_activate = $tracking_id = $save_ga_events = $monitor_email_link = $monitor_tel_link = $monitor_form_submit = '';
    if ($wim_activation_status = get_option('wim_activation_status')) {
      $verification_status = ($wim_activation_status['activation_status'] == 1) ? 'verified' : '';
      $token = $wim_activation_status['token'];
    }
    if ($wim_old_options = get_option('web-instant-messenger')) {
      $wim_activate = ($wim_old_options['activate'] == 1) ? 'on' : '';
      $rag_soc = $wim_old_options['rag_soc'];
      $auto_show_wim = $wim_old_options['auto_show_wim'];
      $show_wim_after = $wim_old_options['show_wim_after'];
      $show_mobile = $wim_old_options['show_mobile'];
      $lingua = $wim_old_options['lingua'];
      $messaggio_0 = $wim_old_options['messaggio_0'];
      $messaggio_1 = $wim_old_options['messaggio_1'];
    }
    // Check if LW Contact Form 7 Addon plugin is activated
    if (is_plugin_active('lw-contact-form/localweb.php')) {
      $cf7_activate = $save_cf7_subm = 'on';
    }
    //
    if (get_option('gadwp_options')) {
      $gadwp_options = (array) json_decode( get_option( 'gadwp_options' ) );
      $locked_profile = $gadwp_options['tableid_jail'];
      $profiles = $gadwp_options['ga_profiles_list'];
      if (!empty($profiles) ) {
				foreach ( $profiles as $item ) {
					if ( $item[1] == $locked_profile ) {
						$tracking_id = $item[2];
					}
				}
			}
    }
    if (preg_match('/^ua-\d{4,9}-\d{1,4}$/i', strval($tracking_id))) {
      $ga_activate = $save_ga_events = $monitor_email_link = $monitor_tel_link = $monitor_form_submit = 'on';
    }
    if (!get_option(LW_ALL_IN_ONE_PLUGIN_NAME)) {
      $initial_empty_options = array(
        'ga_activate' => $ga_activate,
        'ga_fields' => array(
          'tracking_id' => $tracking_id,
          'save_ga_events' => $save_ga_events,
          'monitor_email_link' => $monitor_email_link,
          'monitor_tel_link' => $monitor_tel_link,
          'monitor_form_submit' => $monitor_form_submit,
        ),
        'wim_activate' => $wim_activate,
        'wim_fields' => array(
          'verification_status' => $verification_status,
          'token' => $token,
          'rag_soc' => $rag_soc,
          'auto_show_wim' => $auto_show_wim,
          'show_wim_after' => $show_wim_after,
          'show_mobile' => $show_mobile,
          'lingua' => $lingua,
          'messaggio_0' => $messaggio_0,
          'messaggio_1' => $messaggio_1,
        ),
        'cf7_activate' => $cf7_activate,
        'lw_cf7_fields' => array(
          'save_cf7_subm' => $save_cf7_subm,
        ),
        'lw_hf_fields' => array(
          'insert_header' => '',
          'insert_footer' => '',
        ),
      );
      add_option(LW_ALL_IN_ONE_PLUGIN_NAME, $initial_empty_options);
    }
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $a_events_table = $wpdb->prefix . LW_ALL_IN_ONE_A_EVENTS_TABLE;
    $cf7_table = $wpdb->prefix . LW_ALL_IN_ONE_CF7_TABLE;

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    if ($wpdb->get_var("show tables like '$a_events_table'") != $a_events_table) {
      $sql1 = "CREATE TABLE $a_events_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            ga_category varchar(250) DEFAULT '' NULL,
            ga_action varchar(250) DEFAULT '' NULL,
            ga_label varchar(250) DEFAULT '' NULL,
            PRIMARY KEY (id)
          ) $charset_collate;";
      dbDelta($sql1);
    }

    if ($wpdb->get_var("show tables like '$cf7_table'") != $cf7_table) {
      $sql2 = "CREATE TABLE $cf7_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            subject text DEFAULT '' NULL,
            message text DEFAULT '' NULL,
            name varchar(150) DEFAULT '' NULL,
            surname varchar(150) DEFAULT '' NULL,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            email varchar(100) DEFAULT '' NULL,
            phone varchar(100) DEFAULT '' NULL,
            tipo_Contratto varchar(10) DEFAULT '' NULL,
            id_Contratto varchar(10) DEFAULT '' NULL,
            submited_page text DEFAULT '' NULL,
            sent varchar(2) DEFAULT '' NULL,
            PRIMARY KEY (id)
          ) $charset_collate;";
      dbDelta($sql2);
    }

    $old_cf7_table = $wpdb->prefix . 'inserimenti_cf';
    $old_cf7_table_transfer_err = array();
    if ($wpdb->get_var("show tables like '$old_cf7_table'") == $old_cf7_table) {
      $inserimenti_cf_results = $wpdb->get_results("SELECT * FROM $old_cf7_table");
      if ($wpdb->num_rows > 0) {
        foreach ($inserimenti_cf_results as $cf) {
          $old_data = array('subject' => $cf->soggetto, 'message' => $cf->messaggio, 'name' => $cf->nome, 'surname' => $cf->cognome, 'time' => $cf->time, 'email' => $cf->email, 'phone' => $cf->telefono, 'tipo_Contratto' => $cf->tipo_Contratto, 'id_Contratto' => $cf->id_Contratto, 'submited_page' => $cf->submited_page, 'sent' => $cf->inviato);
          $format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');
          if (!$wpdb->insert($cf7_table, $old_data, $format)) {
            array_push($old_cf7_table_transfer_err, 'error');
          }
        }
      }
      if (empty($old_cf7_table_transfer_err)) {
        $wpdb->query("DROP TABLE IF EXISTS $old_cf7_table");
      }
    }

    // if (version_compare($version, '1.0.1') < 0) {
    //     $sql = "CREATE TABLE $table_name (
    //     id mediumint(9) NOT NULL AUTO_INCREMENT,
    //     time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    //     views smallint(5) NOT NULL,
    //     clicks smallint(5) NOT NULL,
    //     blog_id smallint(5) NOT NULL,
    //     UNIQUE KEY id (id)
    //   ) $charset_collate;";
    //     dbDelta($sql);

    //     update_option('lw_all_in_one_version', '1.0.1');
    // }
  }

}
