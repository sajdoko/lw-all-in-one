<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://localweb.it/
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
   * @param  string  $plugin_name       The name of the plugin.
   * @param  string  $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   */
  public function enqueue_styles() {

    // $min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
    // wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/lw-all-in-one-public'.$min.'.css', array(), $this->version, 'all');

  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   */
  public function enqueue_scripts(){
    //Plugin options
    $options = get_option($this->plugin_name);
    $ga_activate = (isset($options['ga_activate'])) ? $options['ga_activate'] : '';
    $ga_fields_tracking_id = (isset($options['ga_fields']['tracking_id'])) ? sanitize_text_field($options['ga_fields']['tracking_id']) : '';
    $ga_fields_monitor_woocommerce_data = (isset($options['ga_fields']['monitor_woocommerce_data'])) ? sanitize_text_field($options['ga_fields']['monitor_woocommerce_data']) : '';
    if ($ga_activate === 'on' && $ga_fields_tracking_id !== '') {
      $min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
      wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/lw-all-in-one-public'.$min.'.js', array('jquery'), $this->version, true);
      wp_localize_script($this->plugin_name, 'lw_all_in_one_save_ga_event_object',
        array(
          'ajaxurl' => admin_url('admin-ajax.php'),
          'security' => wp_create_nonce($this->plugin_name),
          // 'data_var_1' => 'value 1',
          // 'data_var_2' => 'value 2',
        )
      );

      if ($ga_fields_monitor_woocommerce_data === 'on') {
        wp_enqueue_script($this->plugin_name.'_woocommerce_gtm', plugin_dir_url(__FILE__) . 'js/lw-all-in-one-woocommerce-gtm'.$min.'.js', array('jquery'), $this->version, true);
      }

    }

  }

  public function include_woocommerce_gtm_tracking() {
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
      include_once 'partials/lw-all-in-one-public-woocommerce-gtm.php';
    }
  }

  public function lw_all_in_one_header_scripts() {
    //Plugin options
    $options = get_option($this->plugin_name);
    $ga_activate = (isset($options['ga_activate'])) ? $options['ga_activate'] : '';
    $ga_fields_tracking_id = (isset($options['ga_fields']['tracking_id'])) ? sanitize_text_field($options['ga_fields']['tracking_id']) : '';
    $ga_fields_save_ga_events = (isset($options['ga_fields']['save_ga_events'])) ? sanitize_text_field($options['ga_fields']['save_ga_events']) : '';
    $ga_fields_monitor_email_link = (isset($options['ga_fields']['monitor_email_link'])) ? sanitize_text_field($options['ga_fields']['monitor_email_link']) : '';
    $ga_fields_monitor_tel_link = (isset($options['ga_fields']['monitor_tel_link'])) ? sanitize_text_field($options['ga_fields']['monitor_tel_link']) : '';
    $ga_fields_monitor_form_submit = (isset($options['ga_fields']['monitor_form_submit'])) ? sanitize_text_field($options['ga_fields']['monitor_form_submit']) : '';

    if ($ga_activate === 'on' && $ga_fields_tracking_id !== '') {
      $tag_type = explode('-', $ga_fields_tracking_id, 2)[0];

      if ($tag_type == 'GTM') {
        echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); })(window,document,'script','dataLayer','$ga_fields_tracking_id');</script>", PHP_EOL;
      } else {
        echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $ga_fields_tracking_id . '"></script>', PHP_EOL;
        echo "<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', '$ga_fields_tracking_id');</script>", PHP_EOL;
      }

      echo '<script>';
      echo 'const lwAioGaActivate=true;';
      echo 'const lwAioTrackingType="'.$tag_type.'";';
      echo ($ga_fields_save_ga_events === 'on') ? 'const lwAioSaveGaEvents=true;' : 'const lwAioSaveGaEvents=false;';
      echo ($ga_fields_monitor_email_link === 'on') ? 'const lwAioMonitorEmailLink=true;' : 'const lwAioMonitorEmailLink=false;';
      echo ($ga_fields_monitor_tel_link === 'on') ? 'const lwAioMonitorTelLink=true;' : 'const lwAioMonitorTelLink=false;';
      echo ($ga_fields_monitor_form_submit === 'on') ? 'const lwAioMonitorFormSubmit=true;' : 'const lwAioMonitorFormSubmit=false;';
      echo '</script>', PHP_EOL;
    }
  }

  public function lw_all_in_one_save_ga_event() {
    if (!check_ajax_referer($this->plugin_name, 'security')) {
      wp_send_json_error(__('Security is not valid!', LW_ALL_IN_ONE_PLUGIN_NAME));
      die();
    }

    if (isset($_POST['action']) && $_POST['action'] == 'lw_all_in_one_save_ga_event') {
      $event_category = sanitize_text_field($_POST['event_category']);
      $event_action = sanitize_text_field($_POST['event_action']);
      $event_label = sanitize_text_field($_POST['event_label']);

      global $wpdb;
      $table = $wpdb->prefix . LW_ALL_IN_ONE_A_EVENTS_TABLE;
      $data = array('time' => current_time('mysql', 1), 'ga_category' => $event_category, 'ga_action' => $event_action, 'ga_label' => $event_label);
      $format = array('%s', '%s', '%s', '%s');
      if ($wpdb->insert($table, $data, $format)) {
        wp_send_json_success(__('Event Saved!', LW_ALL_IN_ONE_PLUGIN_NAME));
      } else {
        wp_send_json_error(__('Event was not Saved!', LW_ALL_IN_ONE_PLUGIN_NAME));
      }
    } else {
      wp_send_json_error(__('Action is not valid!', LW_ALL_IN_ONE_PLUGIN_NAME));
    }
	  die();
  }

  public function lw_all_in_one_dequeue(){
    //Plugin options
    $options = get_option($this->plugin_name);
    $opt_scr_deliv = (isset($options['lw_cf7_fields']['opt_scr_deliv'])) ? $options['lw_cf7_fields']['opt_scr_deliv'] : '';

    if ($opt_scr_deliv === 'on') {
      global $post;
      if ( !has_shortcode( $post->post_content, 'contact-form-7') ) {
        add_action('wp_print_styles', 'lw_all_in_one_dequeue_styles');
        add_action('wp_print_scripts', 'lw_all_in_one_dequeue_scripts');
      }
    }

    function lw_all_in_one_dequeue_styles(){
      global $wp_styles;
      foreach( $wp_styles->queue as $style ) {
        if ( $style == 'contact-form-7' ) {
          wp_dequeue_style($wp_styles->registered[$style]->handle);
        }
      }
    }

    function lw_all_in_one_dequeue_scripts(){
      global $wp_scripts;
      foreach( $wp_scripts->queue as $style ) {
        if ( in_array($style, ['wpcf7-recaptcha', 'google-recaptcha', 'contact-form-7']) ) {
          wp_dequeue_script($wp_scripts->registered[$style]->handle);
        }
      }
    }
  }

}
