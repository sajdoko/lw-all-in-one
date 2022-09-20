<?php
$page_cookie = get_page_by_path('cookie-policy');
$cookie_file = file_get_contents( plugin_dir_path(dirname(__FILE__)) . 'privacy-pages/cookie.html');

$patterns = array();
$patterns[0] = '/replace_cookie_1/';
$patterns[1] = '/replace_cookie_2/';
$replacements = array();
$replacements[0] = $domain;
$replacements[1] = $date;
$cookie_file = wp_filter_post_kses(preg_replace($patterns, $replacements, $cookie_file));

if ($page_cookie->ID != '') {
  $cookie_page = array(
    'ID' => $page_cookie->ID,
    'post_content' => $cookie_file,
    'post_status' => 'publish',
  );
  $post_id = wp_update_post($cookie_page, true);
  if (is_wp_error($post_id)) {
    $errors = $post_id->get_error_messages();
    $create_pages_resposes['cookie-policy']['status'] = 'error';
    $create_pages_resposes['cookie-policy']['message'] = esc_attr__("cookie-policy could not be update!", $this->plugin_name);
    $create_pages_resposes['cookie-policy']['action'] = 'updated';
    $create_pages_resposes['cookie-policy']['post_id'] = $post_id;
  } else {
    $create_pages_resposes['cookie-policy']['status'] = 'success';
    $create_pages_resposes['cookie-policy']['message'] = esc_attr__("cookie-policy updated successfully!", $this->plugin_name);
    $create_pages_resposes['cookie-policy']['action'] = 'updated';
    $create_pages_resposes['cookie-policy']['post_id'] = $post_id;
  }

} else {
  $cookie_page = array(
    'post_title' => 'Cookie Policy',
    'post_status' => 'publish',
    'post_type' => 'page',
    'post_content' => $cookie_file,
  );
  $post_id = wp_insert_post($cookie_page, true);
  if (is_wp_error($post_id)) {
    $create_pages_resposes['cookie-policy']['status'] = 'error';
    $create_pages_resposes['cookie-policy']['message'] = esc_attr__("cookie-policy could not be created!", $this->plugin_name);
    $create_pages_resposes['cookie-policy']['action'] = 'created';
    $create_pages_resposes['cookie-policy']['post_id'] = $post_id;
  } else {
    $create_pages_resposes['cookie-policy']['status'] = 'success';
    $create_pages_resposes['cookie-policy']['message'] = esc_attr__("cookie-policy created successfully!", $this->plugin_name);
    $create_pages_resposes['cookie-policy']['action'] = 'created';
    $create_pages_resposes['cookie-policy']['post_id'] = $post_id;
  }

}

if (is_plugin_active('italy-cookie-choices/italy-cookie-choices.php')) {
  //plugin is activated
  $url = $domain . '/cookie-policy/';
  $text = 'Questo sito utilizza cookies, anche di terze parti per consentire una migliore esperienza di navigazione ed un corretto funzionamento delle pagine web. Per saperne di più, per modificare le impostazioni, per prestare il consenso solo ad alcuni utilizzi o per rimuovere i cookies dal proprio browser una volta installati cliccare <a href="/cookie-policy/">qui</a>. Con la prosecuzione della navigazione, visualizzando il relativo contenuto, o accedendo a un qualunque elemento posto all’esterno di questo banner, il visitatore acconsente espressamente all’uso dei cookie e delle tecnologie similari.';
  $button_text = "Accetto";
  $cookie_name = "accettoConsensoCookie";
  $cookie_value = "si";
  $italy_cookie_choices = get_option('italy_cookie_choices');
  $italy_cookie_choices['text'] = $text;
  $italy_cookie_choices['button_text'] = $button_text;
  $italy_cookie_choices['url'] = $url;
  $italy_cookie_choices['cookie_name'] = $cookie_name;
  $italy_cookie_choices['cookie_value'] = $cookie_value;
  update_option('italy_cookie_choices', $italy_cookie_choices);
} elseif (is_plugin_active('eu-cookie-law/eu-cookie-law.php')) {
  $page_cookie = get_page_by_path('cookie-policy');
  $boxlinkid = $page_cookie->ID;
  $barmessage = 'Questo sito utilizza cookies, anche di terze parti per consentire una migliore esperienza di navigazione ed un corretto funzionamento delle pagine web. Per saperne di più, per modificare le impostazioni, per prestare il consenso solo ad alcuni utilizzi o per rimuovere i cookies dal proprio browser una volta installati cliccare <a href="/cookie-policy/" >qui</a>. Con la prosecuzione della navigazione, visualizzando il relativo contenuto, o accedendo a un qualunque elemento posto all’esterno di questo banner, il visitatore acconsente espressamente all’uso dei cookie e delle tecnologie similari.';
  $barlink = "Cookie Policy";
  $barbutton = "Accetto";
  $peadig_eucookie = get_option('peadig_eucookie');
  $peadig_eucookie['barmessage'] = $barmessage;
  $peadig_eucookie['barlink'] = $barlink;
  $peadig_eucookie['barbutton'] = $barbutton;
  $peadig_eucookie['boxlinkid'] = $boxlinkid;
  update_option('peadig_eucookie', $peadig_eucookie);
} else {
}

if (is_plugin_active('wp-fastest-cache/wpFastestCache.php')) {
  if (isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')) {
    $GLOBALS['wp_fastest_cache']->deleteCache(true);
  }
}