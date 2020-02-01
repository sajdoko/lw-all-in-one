<?php

/**
 * Fired when the plugin is uninstalled.
 * @link       https://localweb.it/
 *
 * @package    Lw_All_In_One
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit;
}

//Plugin options
$options = get_option('lw_all_in_one');

if (isset($options['lw_aio_fields']['delete_data']) && $options['lw_aio_fields']['delete_data'] === 'on') {
  global $wpdb;

  $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}lw_aio_a_events");
  $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}lw_aio_cf7");

  delete_option('lw_all_in_one');

  if (wp_next_scheduled('lw_all_in_one_data_retention')) {
    wp_clear_scheduled_hook( 'lw_all_in_one_data_retention' );
  }
  if (wp_next_scheduled('lw_all_in_one_cf7_sync')) {
    wp_clear_scheduled_hook( 'lw_all_in_one_cf7_sync' );
  }

  wp_cache_flush();
}