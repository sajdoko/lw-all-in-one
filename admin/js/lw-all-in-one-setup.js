(function ($) {
  "use strict";
  $(document).on('click', '#wim_verify_attivation', function (event) {
    event.preventDefault();
    var form_data = new FormData();
    form_data.append('action', 'setup_wizard_wim_verify_attivation');
    form_data.append('security', lwaio_setup_wizard_ajax_object.security);
    $.ajax({
        url: lwaio_setup_wizard_ajax_object.ajaxurl,
        type: 'POST',
        data: form_data,
        contentType: false,
        cache: false,
        processData: false
      })
      .done(function (response) {
        if (response.success === true) {
          $('#verification_status_response').append('<div class="notice notice-success inline"><p>' + response.data['message'] + '</p></div>');
          $('#wim_fields_verification_status_resp').val('verified');
          $('#wim_fields_token_resp').val(response.data.fields['token']);
          $('#wim_fields_auto_show_wim').val(response.data.fields['auto_show_wim']);
          $('#wim_fields_show_wim_after').val(response.data.fields['show_wim_after']);
          $('#wim_fields_show_mobile').val(response.data.fields['show_mobile']);
          $('#wim_fields_lingua').val(response.data.fields['lingua']);
          $('#wim_fields_messaggio_0').val(response.data.fields['messaggio_0']);
          $('#wim_fields_messaggio_1').val(response.data.fields['messaggio_1']);
          $('#wim_verify_attivation').hide();
          // window.setTimeout(
          // 	document.getElementById("lw_all_in_one_options").submit.click(),
          // 	3000
          // );
        } else {
          $('#verification_status_response').append('<div class="notice notice-error inline"><p>' + response.data + '</p></div>');
        }
      });
  });
})(jQuery);