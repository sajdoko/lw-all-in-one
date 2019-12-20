<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://localweb.it/
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin/partials
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $wpdb;
$table_name = $wpdb->prefix . LW_ALL_IN_ONE_CF7_TABLE;
$lw_all_in_one_pagination_html = "";
$lw_all_in_one_items_per_page = 10;
$lw_all_in_one_query = "SELECT * FROM $table_name";
$lw_all_in_one_total_query = "SELECT COUNT(1) FROM (${lw_all_in_one_query}) AS combined_table";
$lw_all_in_one_total = $wpdb->get_var($lw_all_in_one_total_query);
$lw_all_in_one_page = isset($_GET['lw_aio_page']) ? abs((int) $_GET['lw_aio_page']) : 1;
$lw_all_in_one_offset = ($lw_all_in_one_page * $lw_all_in_one_items_per_page) - $lw_all_in_one_items_per_page;
$ga_event_results = $wpdb->get_results($lw_all_in_one_query . " ORDER BY time DESC LIMIT ${lw_all_in_one_offset}, ${lw_all_in_one_items_per_page}");
$lw_all_in_one_total_page = ceil($lw_all_in_one_total / $lw_all_in_one_items_per_page);

if ($lw_all_in_one_total_page > 1) {
    $lw_all_in_one_pagination_html = '<div class="lw-aio-pagination"><span class="lw-aio-pagi-span">Page ' . $lw_all_in_one_page . ' of ' . $lw_all_in_one_total_page . '</span>' . paginate_links(array(
        'base' => add_query_arg('lw_aio_page', '%#%'),
        'format' => '',
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
        'total' => $lw_all_in_one_total_page,
        'current' => $lw_all_in_one_page,
    )) . '<span class="lw-aio-pagi-span-tot">' . $lw_all_in_one_total . __( ' Results', LW_ALL_IN_ONE_PLUGIN_NAME )  . '</span></div>';
}
?>
<div class="wrap">

  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <hr>
  <div id="poststuff" class="lw-aio">
    <div id="post-body" class="metabox-holder columns-2">
      <!-- main content -->
      <div id="post-body-content">
        <div class="postbox">
          <div class="inside">
            <?php if ($lw_all_in_one_total > 0) : ?>
            <table class="lw-aio-settings-options">
              <tr class="lw-aio-table-heading">
                <td><?php esc_attr_e( 'Nr.', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></td>
                <td><?php esc_attr_e( 'Time', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></td>
                <td><?php esc_attr_e( 'Name', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></td>
                <td><?php esc_attr_e( 'Surname', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></td>
                <td><?php esc_attr_e( 'Email', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></td>
                <td><?php esc_attr_e( 'Phone', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></td>
              </tr>
              <?php $nr= $lw_all_in_one_offset +1; foreach ($ga_event_results as $ga_event_result) : ?>
              <tr class="lw-aio-table-row">
                <td><?php echo $nr; ?></td>
                <td><?php echo esc_html($ga_event_result->time); ?></td>
                <td><?php echo esc_html($ga_event_result->name); ?></td>
                <td><?php echo esc_html($ga_event_result->surname); ?></td>
                <td><?php echo esc_html($ga_event_result->email); ?></td>
                <td><?php echo esc_html($ga_event_result->phone); ?></td>
              </tr>
              <?php $nr++; endforeach; ?>
            </table>
            <br class="clear" />
            <?php echo $lw_all_in_one_pagination_html; ?>
            <?php else : ?>
              <h2><?php esc_attr_e( 'There are no contact form 7 submissions saved yet!', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></h2>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>