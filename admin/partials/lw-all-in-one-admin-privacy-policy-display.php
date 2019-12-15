<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://localweb.it/
 * @since      1.0.0
 *
 * @package    Lw_All_In_One
 * @subpackage Lw_All_In_One/admin/partials
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$italy_cookie_choices_missing = false;
?>
<div class="wrap">

  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <?php settings_errors();?>
  <hr>
  <div class="warning">
      <h3><?php esc_attr_e('WARNING!', $this->plugin_name);?></h3>
    <p>
      <?php esc_attr_e('Use this section only if this website\'s domain is registered by LocalWeb Srl.', $this->plugin_name);?>
    </p>
    <p>
      <?php esc_attr_e('Link at FOOTER section pages Privacy Policy and Cookie Policy.', $this->plugin_name);?>
    </p>
    <p>
      <?php esc_attr_e('Link Informativa Trattamento Dati in all contact forms of the website.', $this->plugin_name);?>
    </p>
    <?php if ( !is_plugin_active( 'italy-cookie-choices/italy-cookie-choices.php' ) ) { $italy_cookie_choices_missing = false;?>
      <p class="danger">
        <?php esc_attr_e('Italy Cookie Choices plugin is not active, you must install and active it before creating pages!!!', $this->plugin_name);?>
      </p>
    <?php }; ?>
  </div>
  <hr>
  <form method="post" name="<?php echo $this->plugin_name; ?>_privacy_policy_pages" id="<?php echo $this->plugin_name; ?>_privacy_policy_pages" action="">

    <div id="poststuff" class="lw-aio">
      <div id="post-body" class="metabox-holder columns-2">
        <!-- main content -->
        <div id="post-body-content">
          <div class="postbox">
            <div class="inside">
              <?php if (!$italy_cookie_choices_missing) : ?>
              <h2><?php esc_attr_e('Choose which pages you want to create:', $this->plugin_name);?></h2>
              <table class="lw-aio-settings-options">
                <tbody>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>-cookie-page" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_cookie_page">
                        <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_cookie_page">
                          <div class="lw-aio-settings-custom-switch-inner privacy-page"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Create Cookie Policy page?', $this->plugin_name);?></div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>-privacy-page" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_privacy_page">
                        <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_privacy_page">
                          <div class="lw-aio-settings-custom-switch-inner privacy-page"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Create Privacy Policy page?', $this->plugin_name);?></div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>-trattamento-dati-page" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_trattamento_dati_page">
                        <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_trattamento_dati_page">
                          <div class="lw-aio-settings-custom-switch-inner privacy-page"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Create Information Treatment page?', $this->plugin_name);?></div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <br class="clear">
              <?php submit_button(__('Create Pages', $this->plugin_name), 'primary', 'submit', TRUE);?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

</div>