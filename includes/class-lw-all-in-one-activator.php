<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
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
        global $wpdb;
        // $version = get_option('lw_all_in_one_version', '1.0.0');
        update_option('lw_all_in_one_version', '1.0.0');
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
                deactivate_plugins('lw-contact-form/localweb.php');
                delete_plugins(array('lw-contact-form/localweb.php'));
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
