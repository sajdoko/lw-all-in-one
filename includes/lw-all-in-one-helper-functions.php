<?php
  function clean_domain($domain) {
    $clean = preg_replace('#^http(s)?://#', '', $domain);
    $clean = preg_replace('/^www\./', '', $clean);
    $clean_arr = explode("/", $clean);
    $clean = $clean_arr[0];
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
      "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
      "â€”", "â€“", ",", "<", ">", "/", "?", " ");
    $clean = str_replace($strip, "", strip_tags($clean));
    $clean = (function_exists('mb_strtolower')) ? mb_strtolower($clean, 'UTF-8') : strtolower(utf8_encode($clean));
    $clean = strtolower($clean);
    return $clean;
  }

  function lw_all_in_one_validate_tracking_id($str) {
    return (bool) preg_match('/^ua-\d{4,9}-\d{1,4}$/i', strval($str));
  }
?>