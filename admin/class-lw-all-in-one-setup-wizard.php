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
   * The version of this plugin.
   *
   * @access   private
   * @var      string    $step    Currenct Step.
   */
  private $step;

  /**
   * The version of this plugin.
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
      'google_analytics' => array(
        'name' => __('Google Analytics', LW_ALL_IN_ONE_PLUGIN_NAME),
        'view' => array($this, 'setup_step_google_analytics'),
        'handler' => array($this, 'setup_step_google_analytics_save'),
      ),
      'web_instant_messenger' => array(
        'name' => __('Web Instant Messenger', LW_ALL_IN_ONE_PLUGIN_NAME),
        'view' => array($this, 'setup_step_web_instant_messenger'),
        'handler' => array($this, 'setup_step_web_instant_messenger_save'),
      ),
      'localweb_contact_form_7' => array(
        'name' => __('LocalWeb Contact Form 7', LW_ALL_IN_ONE_PLUGIN_NAME),
        'view' => array($this, 'setup_step_localweb_contact_form_7'),
        'handler' => array($this, 'setup_step_localweb_contact_form_7_save'),
      ),
      'next_steps' => array(
        'name' => __('Ready!', LW_ALL_IN_ONE_PLUGIN_NAME),
        'view' => array($this, 'setup_step_ready'),
        'handler' => '',
      ),
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

  /**
   * Setup Wizard Header
   */
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

  /**
   * Setup Wizard Footer
   */
  public function setup_wizard_footer() {
      ?>
        <?php if ( 'next_steps' === $this->step ) : ?>
            <a class="lwaio-return-to-dashboard" href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Return to the WordPress Dashboard', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></a>
        <?php endif; ?>
        </body>
      </html>
      <?php
  }

  /**
   * Output the steps
   */
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

  /**
   * Output the content for the current step
   */
  public function setup_wizard_content() {
      echo '<div class="lwaio-setup-content">';
      call_user_func( $this->steps[ $this->step ]['view'] );
      echo '</div>';
  }

  public function next_step_buttons() {
    ?>
      <p class="lwaio-setup-actions step">
          <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', LW_ALL_IN_ONE_PLUGIN_NAME ); ?>" name="save_step" />
          <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next"><?php esc_html_e( 'Skip this step', LW_ALL_IN_ONE_PLUGIN_NAME ); ?></a>
          <?php wp_nonce_field( $this->plugin_name . '_setup_wizard' ); ?>
      </p>
    <?php
  }

  /**
   * Introduction step
   */
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
      <h1><?php esc_attr_e('Activate Services', LW_ALL_IN_ONE_PLUGIN_NAME);?></h1>

      <form method="post">
          <table class="lw-aio-settings-options form-table">
            <tbody>
              <tr>
                <td colspan="2" class="lw-aio-settings-title">
                  <div class="button-secondary lw-aio-settings-custom-switch">
                    <input type="checkbox" name="<?php echo $this->plugin_name; ?>[ga_activate]" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_ga_activate">
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
                    <input type="checkbox" name="<?php echo $this->plugin_name; ?>[wim_activate]" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_wim_activate">
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
                    <input type="checkbox" name="<?php echo $this->plugin_name; ?>[cf7_activate]" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_cf7_activate">
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

      $valid = array();
      $valid['ga_activate'] = (isset($_POST['ga_activate']) && $_POST['ga_activate'] === 'on') ? 'on' : '';
      $valid['wim_activate'] = (isset($_POST['wim_activate']) && $_POST['wim_activate'] === 'on') ? 'on' : '';
      $valid['cf7_activate'] = (isset($_POST['cf7_activate']) && $_POST['cf7_activate'] === 'on') ? 'on' : '';

      wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
      exit;
  }

}
