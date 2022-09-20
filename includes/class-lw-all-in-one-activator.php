<?php

/**
 * Fired during plugin activation
 *
 * @link       https://localweb.it/
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/includes
 */

/**
 * Fired during plugin activation.
 *
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
   */
  public static function activate() {
    if (!get_option('lw_all_in_one_version')) {
      add_option('lw_all_in_one_version', LW_ALL_IN_ONE_VERSION);
    }
    $lw_all_in_one_version = get_option('lw_all_in_one_version');
    // Check if Web Instant Messenger options exist
    $verification_status = $token = $wim_activate = $rag_soc = $auto_show_wim = $show_wim_after = $show_mobile = $lingua = $messaggio_0 = $messaggio_1 = $cf7_activate = $save_cf7_subm = $opt_scr_deliv = $ga_activate = $tracking_id = $save_ga_events = $monitor_email_link = $monitor_tel_link = $monitor_form_submit = '';
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
      $cf7_activate = $save_cf7_subm = $opt_scr_deliv = 'on';
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
    if (lw_all_in_one_validate_tracking_id($tracking_id)) {
      $ga_activate = $save_ga_events = $monitor_email_link = $monitor_tel_link = $monitor_form_submit = 'on';
    }
    if (!get_option(LW_ALL_IN_ONE_PLUGIN_NAME)) {
      $initial_attivation_options = array(
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
          'opt_scr_deliv' => $opt_scr_deliv,
        ),
        'lw_hf_fields' => array(
          'insert_header' => '',
          'insert_footer' => '',
        ),
        'lw_aio_fields' => array(
          'delete_data' => '',
          'data_retention' => 'on',
        ),
      );
      add_option(LW_ALL_IN_ONE_PLUGIN_NAME, $initial_attivation_options);
    }
    // if (version_compare($lw_all_in_one_version, '1.4.0') < 0) {
    //   $exiting_options = get_option(LW_ALL_IN_ONE_PLUGIN_NAME);
    //   if ($exiting_options) {
    //     $new_options = array();
    //     $new_options['lw_hf_fields']['insert_header'] = base64_decode($exiting_options['lw_hf_fields']['insert_header']);
    //     $new_options['lw_hf_fields']['insert_footer'] = base64_decode($exiting_options['lw_hf_fields']['insert_footer']);
    //     $new_options['lw_aio_fields']['delete_data'] = '';
    //     $new_options['lw_aio_fields']['data_retention'] = 'on';
    //     $new_options_update = array_merge($exiting_options, $new_options);
    //     update_option( LW_ALL_IN_ONE_PLUGIN_NAME, $new_options_update );
    //   }
    // }
    // update_option('lw_all_in_one_version', LW_ALL_IN_ONE_VERSION);
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
          $new_time = date('Y-m-d H:i:s', $cf->time);
          $old_data = array('subject' => $cf->soggetto, 'message' => $cf->messaggio, 'name' => $cf->nome, 'surname' => $cf->cognome, 'time' => $new_time, 'email' => $cf->email, 'phone' => $cf->telefono, 'tipo_Contratto' => $cf->tipo_Contratto, 'id_Contratto' => $cf->id_Contratto, 'submited_page' => $cf->submited_page, 'sent' => $cf->inviato);
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

    if (is_plugin_active('web-instant-messenger/web-instant-messenger.php')) {
      deactivate_plugins('web-instant-messenger/web-instant-messenger.php');
    }
    if (is_plugin_active('lw-contact-form/localweb.php')) {
      deactivate_plugins('lw-contact-form/localweb.php');
    }
    if (is_plugin_active('lw-cookie-privacy/lw-cookie-privacy.php')) {
      deactivate_plugins('lw-cookie-privacy/lw-cookie-privacy.php');
    }
    if (is_plugin_active('google-analytics-dashboard-for-wp/gadwp.php')) {
      deactivate_plugins('google-analytics-dashboard-for-wp/gadwp.php');
    }
    if (is_plugin_active('wp-fastest-cache/wpFastestCache.php')) {
      if(isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')){
        $GLOBALS['wp_fastest_cache']->deleteCache(true);
      }
    }
  }

}
