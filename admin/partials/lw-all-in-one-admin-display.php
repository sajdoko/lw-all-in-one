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
      $ga_fields_tracking_id = (isset($options['ga_fields']['tracking_id'])) ? $options['ga_fields']['tracking_id'] : '';
      $ga_fields_save_ga_events = (isset($options['ga_fields']['save_ga_events'])) ? $options['ga_fields']['save_ga_events'] : '';
      $ga_fields_monitor_email_link = (isset($options['ga_fields']['monitor_email_link'])) ? $options['ga_fields']['monitor_email_link'] : '';
      $ga_fields_monitor_tel_link = (isset($options['ga_fields']['monitor_tel_link'])) ? $options['ga_fields']['monitor_tel_link'] : '';
      $ga_fields_monitor_form_submit = (isset($options['ga_fields']['monitor_form_submit'])) ? $options['ga_fields']['monitor_form_submit'] : '';
      $wim_activate = (isset($options['wim_activate'])) ? $options['wim_activate'] : '';
      $wim_fields_verification_status = (isset($options['wim_fields']['verification_status'])) ? $options['wim_fields']['verification_status'] : '';
      $wim_fields_token = (isset($options['wim_fields']['token'])) ? $options['wim_fields']['token'] : '';
      $wim_fields_rag_soc = (isset($options['wim_fields']['rag_soc'])) ? $options['wim_fields']['rag_soc'] : '';
      $wim_fields_auto_show_wim = (isset($options['wim_fields']['auto_show_wim'])) ? $options['wim_fields']['auto_show_wim'] : '';
      $wim_fields_show_wim_after = (isset($options['wim_fields']['show_wim_after'])) ? $options['wim_fields']['show_wim_after'] : '';
      $wim_fields_show_mobile = (isset($options['wim_fields']['show_mobile'])) ? $options['wim_fields']['show_mobile'] : '';
      $wim_fields_lingua = (isset($options['wim_fields']['lingua'])) ? $options['wim_fields']['lingua'] : '';
      $wim_fields_messaggio_0 = (isset($options['wim_fields']['messaggio_0'])) ? $options['wim_fields']['messaggio_0'] : '';
      $wim_fields_messaggio_1 = (isset($options['wim_fields']['messaggio_1'])) ? $options['wim_fields']['messaggio_1'] : '';
      $cf7_activate = (isset($options['cf7_activate'])) ? $options['cf7_activate'] : '';
      $lw_cf7_fields_save_cf7_subm = (isset($options['lw_cf7_fields']['save_cf7_subm'])) ? $options['lw_cf7_fields']['save_cf7_subm'] : '';
echo "<pre>";
print_r($options);
echo "</pre>";
      settings_fields($this->plugin_name);
      do_settings_sections($this->plugin_name);

      if ($ga_activate === 'on') {
        $default_tab = 'tab_ga_events';
      } else if ($wim_activate === 'on') {
        $default_tab = 'tab_wim';
      } else if ($cf7_activate === 'on') {
        $default_tab = 'tab_cf7';
      } else {
        $default_tab = '';
      }
      $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $default_tab;
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
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>[cf7_activate]" class="lw-aio-settings-custom-switch-checkbox" id="<?php echo $this->plugin_name; ?>_cf7_activate" <?php echo ($cf7_activate === 'on') ? 'checked="checked"' : '';?>>
                        <label class="lw-aio-settings-custom-switch-label" for="<?php echo $this->plugin_name; ?>_cf7_activate">
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
              <a href="?page=<?php echo $this->plugin_name; ?>&tab=tab_ga_events" class="nav-tab <?php echo $active_tab == 'tab_ga_events' ? 'nav-tab-active' : ''; ?><?php echo $ga_activate != 'on' ? ' d-none' : ''; ?>"><?php esc_attr_e('Google Analytics', $this->plugin_name);?></a>
              <a href="?page=<?php echo $this->plugin_name; ?>&tab=tab_wim" class="nav-tab <?php echo $active_tab == 'tab_wim' ? 'nav-tab-active' : ''; ?><?php echo $wim_activate != 'on' ? ' d-none' : ''; ?>"><?php esc_attr_e('Web Instant Messenger', $this->plugin_name);?></a>
              <a href="?page=<?php echo $this->plugin_name; ?>&tab=tab_cf7" class="nav-tab <?php echo $active_tab == 'tab_cf7' ? 'nav-tab-active' : ''; ?><?php echo $cf7_activate != 'on' ? ' d-none' : ''; ?>"><?php esc_attr_e('LocalWeb Contact Form 7', $this->plugin_name);?></a>
            </h2>
            <div id="tab_ga_events" class="tab-content<?php echo $active_tab != 'tab_ga_events' ? ' d-none' : ''; ?>">
              <table class="lw-aio-settings-options<?php echo $ga_activate != 'on' ? ' d-none' : ''; ?>">
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
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>[ga_fields][save_ga_events]" class="lw-aio-settings-custom-switch-checkbox" id="save_ga_events" <?php echo ($ga_fields_save_ga_events === 'on') ? 'checked="checked"' : '';?>>
                        <label class="lw-aio-settings-custom-switch-label" for="save_ga_events">
                          <div class="lw-aio-settings-custom-switch-inner"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Save Google Analytics events locally on the database?', $this->plugin_name);?></div>
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
              <table class="lw-aio-settings-options">
                <tbody>
                  <tr>
                    <td colspan="2"><h2><?php esc_attr_e('Web Instant Messenger Options', $this->plugin_name);?></h2></td>
                  </tr>
                  <?php if($wim_fields_verification_status != 1) : ?>
                    <tr><td><div id="verification_status_response"></div></td></tr>
                    <tr><td colspan="2"><?php submit_button(__('Verify Activation', $this->plugin_name), 'primary', 'wim_verify_attivation', TRUE);?></td></tr>
                  <?php else: ?>
                  <tr>
                    <td class="lw-aio-settings-title">
                      <label for="rag_soc"><?php esc_attr_e('Business Name', $this->plugin_name);?></label>
                    </td>
                    <td>
                      <input type="text" id="rag_soc" name="<?php echo $this->plugin_name; ?>[wim_fields][rag_soc]" value="<?php echo !empty($wim_fields_rag_soc) ? $wim_fields_rag_soc : substr(get_option('blogname'), 0, 20) . ' ...';?>" maxlength="20">
                    </td>
                    <td>
                      <?php esc_attr_e('Insert here your business name which will appear on the chat header', $this->plugin_name);?>
                    </td>
                  </tr>
                  <tr>
                    <td class="lw-aio-settings-title">
                      <label for="auto_show_wim"><?php esc_attr_e('Auto show WIM', $this->plugin_name);?></label>
                    </td>
                    <td>
                      <select name="<?php echo $this->plugin_name; ?>[wim_fields][auto_show_wim]">
                          <option value="SI" <?php selected($wim_fields_auto_show_wim, 'SI', TRUE ); ?>><?php esc_attr_e('YES', $this->plugin_name);?></option>
                          <option value="NO" <?php selected($wim_fields_auto_show_wim, 'NO', TRUE ); ?>><?php esc_attr_e('NO', $this->plugin_name);?></option>
                      </select>
                    </td>
                  </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <div id="tab_cf7" class="tab-content<?php echo $active_tab != 'tab_cf7' ? ' d-none' : ''; ?>">
              <table class="lw-aio-settings-options">
                <tbody>
                  <tr>
                    <td colspan="2"><h2><?php esc_attr_e('Contact Form 7 Addon Options', $this->plugin_name);?></h2></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="lw-aio-settings-title">
                      <div class="button-secondary lw-aio-settings-custom-switch">
                        <input type="checkbox" name="<?php echo $this->plugin_name; ?>[lw_cf7_fields][save_cf7_subm]" class="lw-aio-settings-custom-switch-checkbox" id="save_cf7_subm" <?php echo ($lw_cf7_fields_save_cf7_subm === 'on') ? 'checked="checked"' : '';?>>
                        <label class="lw-aio-settings-custom-switch-label" for="save_cf7_subm">
                          <div class="lw-aio-settings-custom-switch-inner"></div>
                          <div class="lw-aio-settings-custom-switch-switch"></div>
                        </label>
                      </div>
                      <div class="switch-desc"> <?php esc_attr_e('Save Contact Form 7 submissions locally on the database?', $this->plugin_name);?></div>
                    </td>
                  </tr>
                </tbody>
              </table>
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