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
?>

<?php if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}?>

<div class="wrap">

  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <hr>
  <?php settings_errors();?>
  <form method="post" name="<?php echo $this->plugin_name; ?>_options" id="<?php echo $this->plugin_name; ?>_options" action="options.php">

    <?php
      //Plugin options
      $options = get_option($this->plugin_name);
      $ga_activate = (isset($options['ga_activate'])) ? $options['ga_activate'] : '';
      $wim_activate = (isset($options['wim_activate'])) ? $options['wim_activate'] : '';
      $lw_cf7 = (isset($options['lw_cf7'])) ? $options['lw_cf7'] : '';
      $ga_fields_tracking_id = (isset($options['ga_fields']['tracking_id'])) ? $options['ga_fields']['tracking_id'] : '';
      $ga_fields_monitor_email_link = (isset($options['ga_fields']['monitor_email_link'])) ? $options['ga_fields']['monitor_email_link'] : '';
      $ga_fields_monitor_tel_link = (isset($options['ga_fields']['monitor_tel_link'])) ? $options['ga_fields']['monitor_tel_link'] : '';
      $ga_fields_monitor_form_submit = (isset($options['ga_fields']['monitor_form_submit'])) ? $options['ga_fields']['monitor_form_submit'] : '';

      settings_fields($this->plugin_name);
      do_settings_sections($this->plugin_name);

      $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'tab_ga_events';
    ?>

    <div id="poststuff" class="lw-aio">
      <div id="post-body" class="metabox-holder columns-2">
        <!-- main content -->
        <div id="post-body-content">
          <div class="postbox">
            <div class="inside">
              <h2><?php esc_attr_e('Activate Services', $this->plugin_name);?></h2>
              <table class="lw-aio-settings-options">
                <tbody>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>[ga_activate]" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_ga_activate" <?php echo ($ga_activate === 'on') ? 'checked="checked"' : '';?>>
                        <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_ga_activate">
                          <div class="lw-aio-settings-custom-switch-inner"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Activate Google Analytics?', $this->plugin_name);?></div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>[wim_activate]" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_wim_activate" <?php echo ($wim_activate === 'on') ? 'checked="checked"' : '';?>>
                        <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_wim_activate">
                          <div class="lw-aio-settings-custom-switch-inner"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Activate Web Instant Messenger?', $this->plugin_name);?></div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>[lw_cf7]" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_lw_cf7" <?php echo ($lw_cf7 === 'on') ? 'checked="checked"' : '';?>>
                        <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_lw_cf7">
                          <div class="lw-aio-settings-custom-switch-inner"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Activate LocalWeb Contact Form 7?', $this->plugin_name);?></div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div id="post-body-content">
          <div class="inside">
            <h2 class="nav-tab-wrapper">
              <a href="?page=<?php echo $this->plugin_name; ?>&tab=tab_ga_events" class="nav-tab <?php echo $active_tab == 'tab_ga_events' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('Google Analytics', $this->plugin_name);?></a>
              <a href="?page=<?php echo $this->plugin_name; ?>&tab=tab_wim" class="nav-tab <?php echo $active_tab == 'tab_wim' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('Web Instant Messenger', $this->plugin_name);?></a>
            </h2>
            <div id="tab_ga_events" class="tab-content<?php echo $active_tab != 'tab_ga_events' ? ' d-none' : ''; ?>">
              <table class="lw-aio-settings-options">
                <tbody>
                  <tr>
                    <td colspan="2"><h2><?php esc_attr_e('Google Analytics Options', $this->plugin_name);?></h2></td>
                  </tr>
                  <tr>
                    <td class="lw-aio-settings-title">
                      <label for="ga_tracking_id">Tracking ID: </label>
                    </td>
                    <td>
                      <input type="text" id="ga_tracking_id" name="<?php echo $this->plugin_name; ?>[ga_fields][tracking_id]" <?php echo ($ga_fields_tracking_id !== '') ? 'value="'.$ga_fields_tracking_id.'"' : '';?> placeholder="UA-XXXXX-XX" size="25">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>[ga_fields][monitor_email_link]" class="lw-aio-settings-custom-switch-checkbox" id="monitor_email_link" <?php echo ($ga_fields_monitor_email_link === 'on') ? 'checked="checked"' : '';?>>
                        <label class="lw-aio-settings-custom-switch-label" for="monitor_email_link">
                          <div class="lw-aio-settings-custom-switch-inner"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Track Email link clicks', $this->plugin_name);?></div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>[ga_fields][monitor_tel_link]" class="lw-aio-settings-custom-switch-checkbox" id="monitor_tel_link" <?php echo ($ga_fields_monitor_tel_link === 'on') ? 'checked="checked"' : '';?>>
                        <label class="lw-aio-settings-custom-switch-label" for="monitor_tel_link">
                          <div class="lw-aio-settings-custom-switch-inner"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Track Telephone link clicks', $this->plugin_name);?></div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>[ga_fields][monitor_form_submit]" class="lw-aio-settings-custom-switch-checkbox" id="monitor_form_submit" <?php echo ($ga_fields_monitor_form_submit === 'on') ? 'checked="checked"' : '';?>>
                        <label class="lw-aio-settings-custom-switch-label" for="monitor_form_submit">
                          <div class="lw-aio-settings-custom-switch-inner"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Track Contact Form submission', $this->plugin_name);?></div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div id="tab_wim" class="tab-content<?php echo $active_tab != 'tab_wim' ? ' d-none' : ''; ?>">
              
            </div>
          </div>
        </div>
        <!-- sidebar -->
        <!-- <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <div class="inside">
                            <p>Everything you see here</p>
                        </div>
                    </div>
                </div>
            </div> -->
      </div>
      <br class="clear">
    </div>

    <?php submit_button(__('Save Options', $this->plugin_name), 'primary', 'submit', TRUE);?>
  </form>
</div>