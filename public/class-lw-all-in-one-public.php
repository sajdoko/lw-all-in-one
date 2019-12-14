<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/sajmirdoko/
 * @since      1.0.0
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/public
 * @author     sajdoko <sajmir.doko@localweb.it>
 */
class Lw_All_In_One_Public {

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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        // wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/lw-all-in-one-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        //Plugin options
        $options = get_option($this->plugin_name);
        $ga_activate = (isset($options['ga_activate'])) ? $options['ga_activate'] : '';
        $ga_fields_tracking_id = (isset($options['ga_fields']['tracking_id'])) ? $options['ga_fields']['tracking_id'] : '';
        if ($ga_activate === 'on' && $ga_fields_tracking_id !== '') {
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/lw-all-in-one-public.js', array('jquery'), $this->version, true);
            wp_localize_script($this->plugin_name, 'lw_all_in_one_save_ga_event_object',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'security' => wp_create_nonce( $this->plugin_name )
                    // 'data_var_1' => 'value 1',
                    // 'data_var_2' => 'value 2',
                )
            );
        }

    }
    public function lw_all_in_one_save_ga_event() {
        if (!check_ajax_referer( $this->plugin_name, 'security' )) {
            wp_send_json_error( __( 'Security is not valid!', $this->plugin_name ) );
            die();
        }

        if (isset($_POST['action']) && $_POST['action'] == 'lw_all_in_one_save_ga_event') {
            $event_category = sanitize_text_field($_POST['event_category']);
            $event_action = sanitize_text_field($_POST['event_action']);
            $event_label = sanitize_text_field($_POST['event_label']);

            global $wpdb;
            $table = $wpdb->prefix.LW_ALL_IN_ONE_DB_TABLE;
            $data = array('time' => current_time('mysql', 1), 'ga_category' => $event_category, 'ga_action' => $event_action, 'ga_label' => $event_label);
            $format = array('%s','%s','%s','%s');
            if ($wpdb->insert($table,$data,$format)) {
                wp_send_json_success( __( 'Event Saved!', $this->plugin_name ) );
                die();
            } else {
                wp_send_json_error( __( 'Event was not Saved!', $this->plugin_name ) );
                die();
            }
        } else {
            wp_send_json_error( __( 'Action is not valid!', $this->plugin_name ) );
            die();
        }
    }

}
