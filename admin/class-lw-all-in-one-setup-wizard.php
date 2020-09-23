<?php

/**
 * Walkthrough to the initial setup upon installation.
 *
 * @link       https://localweb.it/
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 */

/**
 * Walkthrough to the initial setup upon installation.
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin
 * @author     sajdoko <sajmir.doko@localweb.it>
 */
class Lw_All_In_One_Setup_Wizard {

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
   * Currenct Step.
   *
   * @access   private
   * @var      string    $step    Currenct Step.
   */
  private $step;

  /**
   * Steps for the setup wizard.
   *
   * @access   private
   * @var      array    $steps    Steps for the setup wizard.
   */
  private $steps = array();

  /**
   * Initialize the class and set its properties.
   *
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */

  public function __construct($plugin_name, $version) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

    $this->get_initial_plugin_options = get_option($this->plugin_name);

  }

  /**
   * Add admin menus/screens.
   */
  public function lw_all_in_one_setup_wizard_menu_page() {
    add_dashboard_page( '', '', 'manage_options', $this->plugin_name . '_setup_wizard', '' );
  }

  /**
   * Show the setup wizard
   */
  public function lw_all_in_one_setup_wizard_init() {
    // die($_GET['page']);
    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_key($_REQUEST['_wpnonce']), $this->plugin_name . '_setup_wizard')) {
      // return;
    }

    if (current_user_can('manage_options')) {
      update_option($this->plugin_name . '_setup_wizard_ran', '1');
    }

    if (empty($_GET['page']) || $this->plugin_name . '_setup_wizard' !== $_GET['page']) {
      return;
    }

    $this->steps = array(
      'introduction' => array(
        'name' => __('Introduction', LW_ALL_IN_ONE_PLUGIN_NAME),
        'view' => array($this, 'setup_step_intro'),
        'handler' => '',
      ),
      'activate_services' => array(
        'name' => __('Activate Services', LW_ALL_IN_ONE_PLUGIN_NAME),
        'view' => array($this, 'setup_step_activate_services'),
        'handler' => array($this, 'setup_step_activate_services_save'),
      ),
    );

    $this->steps['google_analytics'] = array(
      'name' => __('Google Analytics', LW_ALL_IN_ONE_PLUGIN_NAME),
      'view' => array($this, 'setup_step_google_analytics'),
      'handler' => array($this, 'setup_step_google_analytics_save'),
    );
    $this->steps['web_instant_messenger'] = array(
      'name' => __('WIM', LW_ALL_IN_ONE_PLUGIN_NAME),
      'view' => array($this, 'setup_step_web_instant_messenger'),
      'handler' => array($this, 'setup_step_web_instant_messenger_save'),
    );
    $this->steps['localweb_contact_form_7'] = array(
      'name' => __('LW Contact Form 7', LW_ALL_IN_ONE_PLUGIN_NAME),
      'view' => array($this, 'setup_step_localweb_contact_form_7'),
      'handler' => array($this, 'setup_step_localweb_contact_form_7_save'),
    );
    $this->steps['privacy_policy_pages'] = array(
      'name' => __('Privacy & Policy', LW_ALL_IN_ONE_PLUGIN_NAME),
      'view' => array($this, 'setup_step_privacy_policy'),
      'handler' => array($this, 'setup_step_privacy_policy_save'),
    );
    $this->steps['wizard_finished'] = array(
      'name' => __('Ready!', LW_ALL_IN_ONE_PLUGIN_NAME),
      'view' => array($this, 'setup_step_finished'),
      'handler' => '',
    );

    $this->step = isset($_GET['step']) ? sanitize_key($_GET['step']) : current(array_keys($this->steps));

    wp_enqueue_style($this->plugin_name . '_setup_wizard', plugin_dir_url(__FILE__) . 'css/lw-all-in-one-admin-setup.css', array('dashicons', 'install'));
    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/lw-all-in-one-admin.css', array(), $this->version, 'all');

    wp_register_script($this->plugin_name . '_setup_wizard', plugin_dir_url(__FILE__) . "js/lw-all-in-one-setup.js", array('jquery', 'wp-i18n'), $this->version, true);

    if (!empty($_POST['save_step']) && isset($this->steps[$this->step]['handler'])) {
      call_user_func($this->steps[$this->step]['handler']);
    }

    ob_start();
    $this->setup_wizard_header();
    $this->setup_wizard_steps();
    $this->setup_wizard_content();
    $this->setup_wizard_footer();
    ob_flush();
    exit;
  }

  public function get_next_step_link() {
      $keys = array_keys( $this->steps );
      return add_query_arg( 'step', $keys[ array_search( $this->step, array_keys( $this->steps ) ) + 1 ], remove_query_arg( 'translation_updated' ) );
  }

  public function setup_wizard_header() {
    ?>
      <!DOCTYPE html>
      <html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
      <head>
          <meta name="viewport" content="width=device-width" />
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
          <title><?php esc_html_e( 'LocalWeb All in One - Setup Wizard', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></title>
          <?php wp_print_scripts( $this->plugin_name . '_setup_wizard' ); ?>
          <?php do_action( 'admin_print_styles' ); ?>
          <?php do_action( 'admin_head' ); ?>
      </head>
      <body class="lwaio-setup wp-core-ui">
        <h1 class="lwaio-logo"><a href="<?php echo esc_url( admin_url( 'index.php?page='. $this->plugin_name . '_setup_wizard') ); ?>"><img src="<?php echo trailingslashit(plugin_dir_url(__FILE__)) . 'img/lw-logo.png' ;?>"></a></h1>
    <?php
  }

  public function setup_wizard_footer() {
    if ( 'wizard_finished' === $this->step ) : ?>
            <a class="lwaio-return-to-dashboard" href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Return to the WordPress Dashboard', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></a>
        <?php endif; ?>
        </body>
      </html>
    <?php
  }

  public function setup_wizard_steps() {
      $output_steps = $this->steps;
      array_shift( $output_steps );
      ?>
      <ol class="lwaio-setup-steps">
          <?php foreach ( $output_steps as $step_key => $step ) : ?>
              <li class="<?php
                  if ( $step_key === $this->step ) {
                    echo 'active';
                  } elseif ( array_search( $this->step, array_keys( $this->steps ) ) > array_search( $step_key, array_keys( $this->steps ) ) ) {
                    echo 'done';
                  }
                ?>">
                <a href="<?php echo esc_url( admin_url( 'index.php?page='. $this->plugin_name . '_setup_wizard&step=' . $step_key ) ); ?>"><?php echo esc_html( $step['name'] ); ?></a>
              </li>
          <?php endforeach; ?>
      </ol>
      <?php
  }

  public function setup_wizard_content() {
      echo '<div class="lwaio-setup-content">';
      call_user_func( $this->steps[ $this->step ]['view'] );
      echo '</div>';
  }

  public function next_step_buttons() {
    ?>
      <p class="lwaio-setup-actions step">
          <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', LW_ALL_IN_ONE_PLUGIN_NAME ); ?>" name="save_step" />
          <?php if ( 'activate_services' != $this->step ) : ?>
          <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next"><?php esc_html_e( 'Skip this step', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></a>
          <?php endif; ?>
          <?php wp_nonce_field( $this->plugin_name . '_setup_wizard' ); ?>
      </p>
    <?php
  }

  public function setup_step_intro() {
    ?>
      <h1><?php esc_html_e( 'Welcome to LocalWeb All in One!', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></h1>
      <p><?php echo wp_kses_post( __('This quick setup wizard will help you configure the basic settings. <strong>It’s completely optional and shouldn’t take longer than two minutes.</strong>', LW_ALL_IN_ONE_PLUGIN_NAME ) ); ?></p>
      <p><?php esc_html_e( 'No time right now? If you don’t want to go through the wizard, you can skip and return to the WordPress dashboard. Come back anytime if you change your mind!', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></p>
      <p class="lwaio-setup-actions step">
          <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next"><?php esc_html_e( 'Let\'s Go!', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></a>
          <a href="<?php echo esc_url( wp_get_referer() ? wp_get_referer() : admin_url() ); ?>" class="button button-large"><?php esc_html_e( 'Not right now', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></a>
      </p>
    <?php
  }

  public function setup_step_activate_services() {
    ?>
      <form method="post">
          <table class="lw-aio-settings-options">
            <tbody>
              <tr>
                <td colspan="2"><h2><?php esc_attr_e('Choose the services to activate!', LW_ALL_IN_ONE_PLUGIN_NAME);?></h2></td>
              </tr>
              <tr>
                <td colspan="2" class="lw-aio-settings-title">
                  <div class="button-secondary lw-aio-settings-custom-switch">
                    <input type="checkbox" name="<?php echo $this->plugin_name; ?>[ga_activate]" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_ga_activate" <?php echo ($this->get_initial_plugin_options['ga_activate'] === 'on') ? 'checked="checked"' : '';?>>
                    <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_ga_activate">
                      <div class="lw-aio-settings-custom-switch-inner"></div>
                      <div class="lw-aio-settings-custom-switch-switch"></div>
                    </label>
                  </div>
                  <div class="switch-desc"> <?php esc_attr_e('Activate Google Analytics?', LW_ALL_IN_ONE_PLUGIN_NAME);?></div>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="lw-aio-settings-title">
                  <div class="button-secondary lw-aio-settings-custom-switch">
                    <input type="checkbox" name="<?php echo $this->plugin_name; ?>[wim_activate]" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_wim_activate" <?php echo ($this->get_initial_plugin_options['wim_activate'] === 'on') ? 'checked="checked"' : '';?>>
                    <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_wim_activate">
                      <div class="lw-aio-settings-custom-switch-inner"></div>
                      <div class="lw-aio-settings-custom-switch-switch"></div>
                    </label>
                  </div>
                  <div class="switch-desc"> <?php esc_attr_e('Activate Web Instant Messenger?', LW_ALL_IN_ONE_PLUGIN_NAME);?></div>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="lw-aio-settings-title">
                  <div class="button-secondary lw-aio-settings-custom-switch">
                    <input type="checkbox" name="<?php echo $this->plugin_name; ?>[cf7_activate]" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_cf7_activate" <?php echo ($this->get_initial_plugin_options['cf7_activate'] === 'on') ? 'checked="checked"' : '';?>>
                    <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_cf7_activate">
                      <div class="lw-aio-settings-custom-switch-inner"></div>
                      <div class="lw-aio-settings-custom-switch-switch"></div>
                    </label>
                  </div>
                  <div class="switch-desc"> <?php esc_attr_e('Activate LocalWeb Contact Form 7?', LW_ALL_IN_ONE_PLUGIN_NAME);?></div>
                </td>
              </tr>
            </tbody>
          </table>

          <?php $this->next_step_buttons(); ?>
      </form>
    <?php
  }

  public function setup_step_activate_services_save() {
      check_admin_referer( $this->plugin_name . '_setup_wizard' );
      if (!current_user_can('manage_options')) {
        return;
      }

      $valid = array();
      $valid['ga_activate'] = (isset($_REQUEST['lw_all_in_one']['ga_activate']) && $_REQUEST['lw_all_in_one']['ga_activate'] === 'on') ? 'on' : '';
      $valid['wim_activate'] = (isset($_REQUEST['lw_all_in_one']['wim_activate']) && $_REQUEST['lw_all_in_one']['wim_activate'] === 'on') ? 'on' : '';
      $valid['cf7_activate'] = (isset($_REQUEST['lw_all_in_one']['cf7_activate']) && $_REQUEST['lw_all_in_one']['cf7_activate'] === 'on') ? 'on' : '';

      $exiting_options = $this->get_initial_plugin_options;
      if ($exiting_options) {
        $valid = array_merge($exiting_options, $valid);
      }

      update_option($this->plugin_name, $valid );

      if ( $valid['ga_activate'] != 'on' ) {
        unset( $this->steps['google_analytics'] );
      }
      if ( $valid['wim_activate'] != 'on' ) {
        unset( $this->steps['web_instant_messenger'] );
      }
      if ( $valid['cf7_activate'] != 'on' ) {
        unset( $this->steps['localweb_contact_form_7'] );
      }

      wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
      exit;
  }

  public function setup_step_google_analytics() {
    ?>
      <form method="post">
        <table id="ga_events_table" class="lw-aio-settings-options">
          <tbody>
            <tr>
              <td colspan="2"><h2><?php esc_attr_e('Google Analytics Options', LW_ALL_IN_ONE_PLUGIN_NAME);?></h2></td>
            </tr>
            <tr>
              <td class="lw-aio-settings-title">
                <label for="ga_tracking_id">Tracking ID: </label>
              </td>
              <td>
                <input type="text" id="ga_tracking_id" name="<?php echo $this->plugin_name; ?>[ga_fields][tracking_id]" <?php echo ($this->get_initial_plugin_options['ga_fields']['tracking_id'] !== '') ? 'value="'.$this->get_initial_plugin_options['ga_fields']['tracking_id'].'"' : '';?> placeholder="UA-XXXXX-XX" size="25">
              </td>
            </tr>
            <tr>
              <td colspan="2" class="lw-aio-settings-title">
                <div class="button-secondary lw-aio-settings-custom-switch">
                  <input type="checkbox" name="<?php echo $this->plugin_name; ?>[ga_fields][save_ga_events]" class="lw-aio-settings-custom-switch-checkbox" id="save_ga_events" <?php echo ($this->get_initial_plugin_options['ga_fields']['save_ga_events'] === 'on') ? 'checked="checked"' : '';?>>
                  <label class="lw-aio-settings-custom-switch-label" for="save_ga_events">
                    <div class="lw-aio-settings-custom-switch-inner"></div>
                    <div class="lw-aio-settings-custom-switch-switch"></div>
                  </label>
                </div>
                <div class="switch-desc"> <?php esc_attr_e('Save Google Analytics events locally on the database?', LW_ALL_IN_ONE_PLUGIN_NAME);?></div>
              </td>
            </tr>
            <tr>
              <td colspan="2" class="lw-aio-settings-title">
                <div class="button-secondary lw-aio-settings-custom-switch">
                  <input type="checkbox" name="<?php echo $this->plugin_name; ?>[ga_fields][monitor_email_link]" class="lw-aio-settings-custom-switch-checkbox" id="monitor_email_link" <?php echo ($this->get_initial_plugin_options['ga_fields']['monitor_email_link'] === 'on') ? 'checked="checked"' : '';?>>
                  <label class="lw-aio-settings-custom-switch-label" for="monitor_email_link">
                    <div class="lw-aio-settings-custom-switch-inner"></div>
                    <div class="lw-aio-settings-custom-switch-switch"></div>
                  </label>
                </div>
                <div class="switch-desc"> <?php esc_attr_e('Track Email link clicks', LW_ALL_IN_ONE_PLUGIN_NAME);?></div>
              </td>
            </tr>
            <tr>
              <td colspan="2" class="lw-aio-settings-title">
                <div class="button-secondary lw-aio-settings-custom-switch">
                  <input type="checkbox" name="<?php echo $this->plugin_name; ?>[ga_fields][monitor_tel_link]" class="lw-aio-settings-custom-switch-checkbox" id="monitor_tel_link" <?php echo ($this->get_initial_plugin_options['ga_fields']['monitor_tel_link'] === 'on') ? 'checked="checked"' : '';?>>
                  <label class="lw-aio-settings-custom-switch-label" for="monitor_tel_link">
                    <div class="lw-aio-settings-custom-switch-inner"></div>
                    <div class="lw-aio-settings-custom-switch-switch"></div>
                  </label>
                </div>
                <div class="switch-desc"> <?php esc_attr_e('Track Telephone link clicks', LW_ALL_IN_ONE_PLUGIN_NAME);?></div>
              </td>
            </tr>
            <tr>
              <td colspan="2" class="lw-aio-settings-title">
                <div class="button-secondary lw-aio-settings-custom-switch">
                  <input type="checkbox" name="<?php echo $this->plugin_name; ?>[ga_fields][monitor_form_submit]" class="lw-aio-settings-custom-switch-checkbox" id="monitor_form_submit" <?php echo ($this->get_initial_plugin_options['ga_fields']['monitor_form_submit'] === 'on') ? 'checked="checked"' : '';?>>
                  <label class="lw-aio-settings-custom-switch-label" for="monitor_form_submit">
                    <div class="lw-aio-settings-custom-switch-inner"></div>
                    <div class="lw-aio-settings-custom-switch-switch"></div>
                  </label>
                </div>
                <div class="switch-desc"> <?php esc_attr_e('Track Contact Form submission', LW_ALL_IN_ONE_PLUGIN_NAME);?></div>
              </td>
            </tr>
          </tbody>
        </table>
          <?php $this->next_step_buttons(); ?>
      </form>
    <?php
  }

  public function setup_step_google_analytics_save() {
      check_admin_referer( $this->plugin_name . '_setup_wizard' );
      if (!current_user_can('manage_options')) {
        return;
      }

      $valid = array();
      $valid['ga_fields']['tracking_id'] = (isset($_REQUEST['lw_all_in_one']['ga_fields']['tracking_id'])) ? sanitize_text_field($_REQUEST['lw_all_in_one']['ga_fields']['tracking_id']) : '';
      $valid['ga_fields']['save_ga_events'] = (isset($_REQUEST['lw_all_in_one']['ga_fields']['save_ga_events']) && $_REQUEST['lw_all_in_one']['ga_fields']['save_ga_events'] === 'on') ? 'on' : '';
      $valid['ga_fields']['monitor_email_link'] = (isset($_REQUEST['lw_all_in_one']['ga_fields']['monitor_email_link']) && $_REQUEST['lw_all_in_one']['ga_fields']['monitor_email_link'] === 'on') ? 'on' : '';
      $valid['ga_fields']['monitor_tel_link'] = (isset($_REQUEST['lw_all_in_one']['ga_fields']['monitor_tel_link']) && $_REQUEST['lw_all_in_one']['ga_fields']['monitor_tel_link'] === 'on') ? 'on' : '';
      $valid['ga_fields']['monitor_form_submit'] = (isset($_REQUEST['lw_all_in_one']['ga_fields']['monitor_form_submit']) && $_REQUEST['lw_all_in_one']['ga_fields']['monitor_form_submit'] === 'on') ? 'on' : '';

      $exiting_options = $this->get_initial_plugin_options;
      if ($exiting_options) {
        $valid = array_merge($exiting_options, $valid);
      }
      if (!$this->lw_all_in_one_validate_tracking_id($valid['ga_fields']['tracking_id'])) {
        wp_redirect( $_REQUEST['_wp_http_referer'] );
      } else {
        update_option($this->plugin_name, $valid );
        wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
      }
      exit;
  }

  public function setup_step_web_instant_messenger() {
    ?>
      <form method="post">
        <table class="lw-aio-settings-options">
          <tbody>
            <tr>
              <td colspan="2"><h2><?php esc_attr_e('Web Instant Messenger Options', LW_ALL_IN_ONE_PLUGIN_NAME);?></h2></td>
            </tr>
            <?php if($this->get_initial_plugin_options['wim_fields']['verification_status'] == 'verified' && strlen($this->get_initial_plugin_options['wim_fields']['token']) == 32) : ?>
            <tr>
              <td class="lw-aio-settings-title-wim">
                <label for="rag_soc"><?php esc_attr_e('Business Name', LW_ALL_IN_ONE_PLUGIN_NAME);?></label>
              </td>
              <td class="lw-aio-settings-field-wim">
                <input type="text" id="rag_soc" name="<?php echo $this->plugin_name; ?>[wim_fields][rag_soc]" value="<?php echo ($this->get_initial_plugin_options['wim_fields']['rag_soc'] != '') ? $this->get_initial_plugin_options['wim_fields']['rag_soc'] : substr(get_option('blogname'), 0, 16) . '...';?>" maxlength="20">
              </td>
              <td>
                <?php esc_attr_e('Insert here your business name which will appear on the chat header', LW_ALL_IN_ONE_PLUGIN_NAME);?>
              </td>
            </tr>
            <tr>
              <td class="lw-aio-settings-title-wim">
                <label for="auto_show_wim"><?php esc_attr_e('Auto show WIM', LW_ALL_IN_ONE_PLUGIN_NAME);?></label>
              </td>
              <td class="lw-aio-settings-field-wim">
                <select name="<?php echo $this->plugin_name; ?>[wim_fields][auto_show_wim]" id="auto_show_wim">
                    <option value="SI" <?php selected($this->get_initial_plugin_options['wim_fields']['auto_show_wim'], 'SI', TRUE ); ?>><?php esc_attr_e('YES', LW_ALL_IN_ONE_PLUGIN_NAME);?></option>
                    <option value="NO" <?php selected($this->get_initial_plugin_options['wim_fields']['auto_show_wim'], 'NO', TRUE ); ?>><?php esc_attr_e('NO', LW_ALL_IN_ONE_PLUGIN_NAME);?></option>
                </select>
              </td>
            </tr>
            <tr>
              <td class="lw-aio-settings-title-wim">
                <label for="show_wim_after"><?php esc_attr_e('Auto show WIM after', LW_ALL_IN_ONE_PLUGIN_NAME);?></label>
              </td>
              <td class="lw-aio-settings-field-wim">
                <select id="show_wim_after" name="<?php echo $this->plugin_name;?>[wim_fields][show_wim_after]">
                    <option value="5" <?php selected( $this->get_initial_plugin_options['wim_fields']['show_wim_after'], '5', TRUE ); ?>><?php _e('5s', LW_ALL_IN_ONE_PLUGIN_NAME);?></option>
                    <option value="10" <?php selected( $this->get_initial_plugin_options['wim_fields']['show_wim_after'], '10', TRUE ); ?>><?php _e('10s', LW_ALL_IN_ONE_PLUGIN_NAME);?></option>
                    <option value="20" <?php selected( $this->get_initial_plugin_options['wim_fields']['show_wim_after'], '20', TRUE ); ?>><?php _e('20s', LW_ALL_IN_ONE_PLUGIN_NAME);?></option>
                    <option value="30" <?php selected( $this->get_initial_plugin_options['wim_fields']['show_wim_after'], '30', TRUE ); ?>><?php _e('30s', LW_ALL_IN_ONE_PLUGIN_NAME);?></option>
                    <option value="45" <?php selected( $this->get_initial_plugin_options['wim_fields']['show_wim_after'], '45', TRUE ); ?>><?php _e('45s', LW_ALL_IN_ONE_PLUGIN_NAME);?></option>
                    <option value="60" <?php selected( $this->get_initial_plugin_options['wim_fields']['show_wim_after'], '60', TRUE ); ?>><?php _e('60s', LW_ALL_IN_ONE_PLUGIN_NAME);?></option>
                </select>
              </td>
            </tr>
            <tr>
              <td class="lw-aio-settings-title-wim">
                <label for="show_mobile"><?php esc_attr_e('Show On Mobile', LW_ALL_IN_ONE_PLUGIN_NAME);?></label>
              </td>
              <td class="lw-aio-settings-field-wim">
                <select id="show_mobile" name="<?php echo $this->plugin_name;?>[wim_fields][show_mobile]">
                    <option value="SI" <?php selected( $this->get_initial_plugin_options['wim_fields']['show_mobile'], 'SI', TRUE ); ?>><?php _e('YES', LW_ALL_IN_ONE_PLUGIN_NAME);?></option>
                    <option value="NO" <?php selected( $this->get_initial_plugin_options['wim_fields']['show_mobile'], 'NO', TRUE ); ?>><?php _e('NO', LW_ALL_IN_ONE_PLUGIN_NAME);?></option>
                </select>
              </td>
            </tr>
            <tr>
              <td class="lw-aio-settings-title-wim">
                <label for="lingua"><?php esc_attr_e('Language', LW_ALL_IN_ONE_PLUGIN_NAME);?></label>
              </td>
              <td class="lw-aio-settings-field-wim">
                <select id="lingua" name="<?php echo $this->plugin_name;?>[wim_fields][lingua]">
                  <option value="it" <?php selected( $this->get_initial_plugin_options['wim_fields']['lingua'], 'it', TRUE ); ?>><?php _e('IT', LW_ALL_IN_ONE_PLUGIN_NAME);?></option>
                  <option value="en" <?php selected( $this->get_initial_plugin_options['wim_fields']['lingua'], 'en', TRUE ); ?>><?php _e('EN', LW_ALL_IN_ONE_PLUGIN_NAME);?></option>
                </select>
              </td>
            </tr>
            <tr>
              <td class="lw-aio-settings-title-wim">
                <label for="messaggio_0"><?php esc_attr_e('Automatic Message 0', LW_ALL_IN_ONE_PLUGIN_NAME);?></label>
              </td>
              <td class="lw-aio-settings-field-wim">
                <textarea id="messaggio_0" name="<?php echo $this->plugin_name;?>[wim_fields][messaggio_0]" maxlength="250" cols="55" rows="3" class=""><?php echo ($this->get_initial_plugin_options['wim_fields']['messaggio_0'] != '') ? $this->get_initial_plugin_options['wim_fields']['messaggio_0'] : 'Salve! Come posso esserle utile?';?></textarea>
              </td>
            </tr>
            <tr>
              <td class="lw-aio-settings-title-wim">
                <label for="messaggio_1"><?php esc_attr_e('Automatic Message 1', LW_ALL_IN_ONE_PLUGIN_NAME);?></label>
              </td>
              <td class="lw-aio-settings-field-wim">
                <textarea id="messaggio_1" name="<?php echo $this->plugin_name;?>[wim_fields][messaggio_1]" maxlength="250" cols="55" rows="3" class=""><?php echo ($this->get_initial_plugin_options['wim_fields']['messaggio_1'] != '') ? $this->get_initial_plugin_options['wim_fields']['messaggio_1'] : 'Gentilmente, mi può lasciare un contatto telefonico o email in modo da poterla eventualmente ricontattare?';?></textarea>

                <input type="hidden" name="<?php echo $this->plugin_name;?>[wim_fields][token]" value="<?php echo ($this->get_initial_plugin_options['wim_fields']['token'] != '') ? $this->get_initial_plugin_options['wim_fields']['token'] : '';?>"/>
                <input type="hidden" name="<?php echo $this->plugin_name;?>[wim_fields][verification_status]" value="<?php echo ($this->get_initial_plugin_options['wim_fields']['verification_status'] != '') ? $this->get_initial_plugin_options['wim_fields']['verification_status'] : '';?>" />
                <input type="hidden" name="<?php echo $this->plugin_name;?>[wim_fields][save_wim_options]" />
              </td>
            </tr>
            <?php else: ?>
            <tr>
              <td>
                <div id="verification_status_response"></div>
                  <input type="hidden" id="wim_fields_verification_status_resp" value="" name="<?php echo $this->plugin_name;?>[wim_fields][verification_status]"/>
                  <input type="hidden" id="wim_fields_token_resp" value="" name="<?php echo $this->plugin_name;?>[wim_fields][token]"/>
                  <input type="hidden" value="<?php echo substr(get_option('blogname'), 0, 16) . '...'; ?>" name="<?php echo $this->plugin_name;?>[wim_fields][rag_soc]"/>
                  <input type="hidden" id="wim_fields_auto_show_wim" value="" name="<?php echo $this->plugin_name;?>[wim_fields][auto_show_wim]"/>
                  <input type="hidden" id="wim_fields_show_wim_after" value="" name="<?php echo $this->plugin_name;?>[wim_fields][show_wim_after]"/>
                  <input type="hidden" id="wim_fields_show_mobile" value="" name="<?php echo $this->plugin_name;?>[wim_fields][show_mobile]"/>
                  <input type="hidden" id="wim_fields_lingua" value="" name="<?php echo $this->plugin_name;?>[wim_fields][lingua]"/>
                  <input type="hidden" id="wim_fields_messaggio_0" value="" name="<?php echo $this->plugin_name;?>[wim_fields][messaggio_0]"/>
                  <input type="hidden" id="wim_fields_messaggio_1" value="" name="<?php echo $this->plugin_name;?>[wim_fields][messaggio_1]"/>
                  <input type="hidden" name="<?php echo $this->plugin_name;?>[wim_fields][save_wim_options]"/>
                </td>
              </tr>
            <tr><td colspan="2"><?php submit_button(__('Verify Activation', LW_ALL_IN_ONE_PLUGIN_NAME), 'secondary', 'wim_verify_attivation', TRUE);?></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
        <?php $this->next_step_buttons(); ?>
      </form>
    <?php
  }

  public function setup_step_web_instant_messenger_save() {
      check_admin_referer( $this->plugin_name . '_setup_wizard' );
      if (!current_user_can('manage_options')) {
        return;
      }
      wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
      exit;
  }

  public function setup_step_localweb_contact_form_7() {
      ?>
      <h1><?php esc_attr_e('Activate Services', LW_ALL_IN_ONE_PLUGIN_NAME);?></h1>

      <form method="post">
          <?php $this->next_step_buttons(); ?>
      </form>
      <?php
  }

  public function setup_step_localweb_contact_form_7_save() {
      check_admin_referer( $this->plugin_name . '_setup_wizard' );
      if (!current_user_can('manage_options')) {
        return;
      }
      wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
      exit;
  }

  public function setup_step_privacy_policy() {
      ?>
      <h1><?php esc_attr_e('Activate Services', LW_ALL_IN_ONE_PLUGIN_NAME);?></h1>

      <form method="post">
          <?php $this->next_step_buttons(); ?>
      </form>
      <?php
  }

  public function setup_step_privacy_policy_save() {
      check_admin_referer( $this->plugin_name . '_setup_wizard' );
      if (!current_user_can('manage_options')) {
        return;
      }
      wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
      exit;
  }

  public function setup_step_finished() {
    ?>

    <div class="final-step">
        <h1><?php esc_html_e( 'Your Site is Ready!', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></h1>
    </div>
    <?php
  }

  public function lw_all_in_one_validate_tracking_id($str) {
    return (bool) preg_match('/^ua-\d{4,9}-\d{1,4}$/i', strval($str));
  }

}
