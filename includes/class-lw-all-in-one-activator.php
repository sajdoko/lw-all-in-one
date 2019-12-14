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
        $table_name = $wpdb->prefix . LW_ALL_IN_ONE_DB_TABLE;

        $sql = "CREATE TABLE $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
          ga_category varchar(250) DEFAULT '' NULL,
          ga_action varchar(250) DEFAULT '' NULL,
          ga_label varchar(250) DEFAULT '' NULL,
          UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

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
