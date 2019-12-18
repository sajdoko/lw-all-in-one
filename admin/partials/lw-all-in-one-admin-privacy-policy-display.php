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
//Plugin options
$options = get_option($this->plugin_name . '_privacy_pages');
$cookie_page_options = (isset($options['cookie-policy'])) ? $options['cookie-policy'] : '';
$privacy_page_options = (isset($options['informativa-sul-trattamento-dei-dati-personali'])) ? $options['informativa-sul-trattamento-dei-dati-personali'] : '';
$trattamento_dati_page_options = (isset($options['informativa-trattamento-dati'])) ? $options['informativa-trattamento-dati'] : '';
?>
<div class="wrap">

  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <?php settings_errors();?>
  <hr>
  <div class="warning">
      <h3><?php esc_attr_e('WARNING!', LW_ALL_IN_ONE_PLUGIN_NAME);?></h3>
    <p>
      <?php esc_attr_e('Use this section only if this website\'s domain is registered by LocalWeb Srl.', LW_ALL_IN_ONE_PLUGIN_NAME);?>
    </p>
    <p>
      <?php esc_attr_e('Link at FOOTER section pages Informativa sul trattamento dei dati personali and Cookie Policy.', LW_ALL_IN_ONE_PLUGIN_NAME);?>
    </p>
    <p>
      <?php esc_attr_e('Link Informativa Trattamento Dati in all contact forms of the website.', LW_ALL_IN_ONE_PLUGIN_NAME);?>
    </p>
    <?php if ( !is_plugin_active( 'italy-cookie-choices/italy-cookie-choices.php' ) ) { $italy_cookie_choices_missing = true;?>
      <p class="danger">
        <?php printf(esc_attr__('%s plugin is not active, you must install and active it before creating pages!!!', LW_ALL_IN_ONE_PLUGIN_NAME), '<a href="plugin-install.php?s=Italy+Cookie+Choices+(for+EU+Cookie+Law)&tab=search&type=term">Italy Cookie Choices (for EU Cookie Law)</a>');?>
      </p>
    <?php }; ?>
  </div>
  <hr>
<?php if (!$italy_cookie_choices_missing) : ?>
  <form method="post" name="<?php echo $this->plugin_name; ?>_privacy_policy_pages" id="<?php echo $this->plugin_name; ?>_privacy_policy_pages" action="">

    <div id="poststuff" class="lw-aio">
      <div id="post-body" class="metabox-holder columns-2">
        <!-- main content -->
        <div id="post-body-content">
          <div class="postbox">
            <div class="inside">
              <h2><?php esc_attr_e('Choose which pages you want to create:', LW_ALL_IN_ONE_PLUGIN_NAME);?></h2>
              <table class="lw-aio-settings-options">
                <tbody>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                    <?php if (!empty($cookie_page_options)) : ?>
                    <p><?php esc_attr_e('Cookie Policy page already created: ', LW_ALL_IN_ONE_PLUGIN_NAME);?> <a href="<?php echo get_permalink($cookie_page_options['post_id']); ?>" target="_blank"><?php echo get_the_title($cookie_page_options['post_id']); ?></a></p>
                    <?php else : ?>
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>_cookie_page" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_cookie_page" <?php echo (!empty($cookie_page_options)) ? 'checked="checked"' : '';?>>
                        <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_cookie_page">
                          <div class="lw-aio-settings-custom-switch-inner privacy-page"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Create Cookie Policy page?', LW_ALL_IN_ONE_PLUGIN_NAME);?></div>
                    <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                    <?php if (!empty($privacy_page_options)) : ?>
                    <p><?php esc_attr_e('Privacy Policy page already created: ', LW_ALL_IN_ONE_PLUGIN_NAME);?> <a href="<?php echo get_permalink($privacy_page_options['post_id']); ?>" target="_blank"><?php echo get_the_title($privacy_page_options['post_id']); ?></a></p>
                    <?php else : ?>
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>_privacy_page" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_privacy_page" <?php echo (!empty($privacy_page_options)) ? 'checked="checked"' : '';?>>
                        <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_privacy_page">
                          <div class="lw-aio-settings-custom-switch-inner privacy-page"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Create Privacy Policy page?', LW_ALL_IN_ONE_PLUGIN_NAME);?></div>
                    <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                    <?php if (!empty($trattamento_dati_page_options)) : ?>
                    <p><?php esc_attr_e('Information Treatment page already created: ', LW_ALL_IN_ONE_PLUGIN_NAME);?> <a href="<?php echo get_permalink($trattamento_dati_page_options['post_id']); ?>" target="_blank"><?php echo get_the_title($trattamento_dati_page_options['post_id']); ?></a></p>
                    <?php else : ?>
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>_trattamento_dati_page" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_trattamento_dati_page" <?php echo (!empty($trattamento_dati_page_options)) ? 'checked="checked"' : '';?>>
                        <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_trattamento_dati_page">
                          <div class="lw-aio-settings-custom-switch-inner privacy-page"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Create Information Treatment page?', LW_ALL_IN_ONE_PLUGIN_NAME);?></div>
                    <?php endif; ?>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div id="created_pages_response"></div>
              <!-- <br class="clear"> -->
                <?php if(is_array($options) && count($options) == 3) : ?>
                <?php else: ?>
                  <?php submit_button(__('Create Pages', LW_ALL_IN_ONE_PLUGIN_NAME), 'primary', 'submit_create_pages', TRUE);?>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
<?php endif; ?>

</div>