<?php

$patterns = array();
$patterns[0] = '/replace_contact_2/';
$replacements = array();
$replacements[0] = $date;
$contact_file = wp_filter_post_kses(preg_replace($patterns, $replacements, $contact_file));

if ($page_contact->ID != '') {
  $contact_page = array(
    'ID' => $page_contact->ID,
    'post_content' => $contact_file,
    'post_status' => 'publish',
  );
  $post_id = wp_update_post($contact_page, true);
  if (is_wp_error($post_id)) {
    $create_pages_resposes['informativa-trattamento-dati']['status'] = 'error';
    $create_pages_resposes['informativa-trattamento-dati']['message'] = esc_attr__("informativa-trattamento-dati could not be update!", $this->plugin_name);
    $create_pages_resposes['informativa-trattamento-dati']['action'] = 'updated';
    $create_pages_resposes['informativa-trattamento-dati']['post_id'] = $post_id;
  } else {
    $create_pages_resposes['informativa-trattamento-dati']['status'] = 'success';
    $create_pages_resposes['informativa-trattamento-dati']['message'] = esc_attr__("informativa-trattamento-dati updated successfully!", $this->plugin_name);
    $create_pages_resposes['informativa-trattamento-dati']['action'] = 'updated';
    $create_pages_resposes['informativa-trattamento-dati']['post_id'] = $post_id;
  }

} else {
  $contact_page = array(
    'post_title' => $post_title,
    'post_status' => 'publish',
    'post_type' => 'page',
    'post_content' => $contact_file,
  );
  $post_id = wp_insert_post($contact_page, true);
  if (is_wp_error($post_id)) {
    $create_pages_resposes['informativa-trattamento-dati']['status'] = 'error';
    $create_pages_resposes['informativa-trattamento-dati']['message'] = esc_attr__("informativa-trattamento-dati could not be created!", $this->plugin_name);
    $create_pages_resposes['informativa-trattamento-dati']['action'] = 'created';
    $create_pages_resposes['informativa-trattamento-dati']['post_id'] = $post_id;
  } else {
    $create_pages_resposes['informativa-trattamento-dati']['status'] = 'success';
    $create_pages_resposes['informativa-trattamento-dati']['message'] = esc_attr__("informativa-trattamento-dati created successfully!", $this->plugin_name);
    $create_pages_resposes['informativa-trattamento-dati']['action'] = 'created';
    $create_pages_resposes['informativa-trattamento-dati']['post_id'] = $post_id;
  }

}

// if (is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
//   //plugin is activated
//   $args = array(
//     'post_type' => 'wpcf7_contact_form',
//     'order' => 'ASC',
//   );

//   $postet = get_posts($args);

//   // The Loop
//   if ($postet != '') {
//     foreach ($postet as $posti) {
//       //PC::debug($posti, 'posti');
//       $content = $posti->post_content;
//       $new_path = 'informativa-trattamento-dati';
//       $content = preg_replace('/informativa-sul-trattamento-dei-dati-personali/', $new_path, $content);

//       $contact_content = array(
//         'ID' => $posti->ID,
//         'post_content' => $content,
//       );
//       $update3 = wp_update_post($contact_content, true);

//       $forma = get_post_meta($posti->ID, '_form');
//       $forma = preg_replace('/informativa-sul-trattamento-dei-dati-personali/', $new_path, $forma);

//       update_post_meta($posti->ID, '_form', $forma[0]);

//     }
//   }

// }