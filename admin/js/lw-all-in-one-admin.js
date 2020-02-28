(function ($) {
	"use strict";
	const { __, _x, _n, _nx } = wp.i18n;
	$(document).ready(function () {
		function addEventDisplayMessage(type, message) {
			$('#add_event_response').html(`<p class="notice notice-${type}">${message}</p>`);
			setTimeout(function(){ $('#add_event_response').html('') }, 3000);
		}
		$("#lw_all_in_one_privacy_policy_pages").submit(function (event) {
			event.preventDefault();
			var form_data = new FormData(this);
			form_data.append('action', 'lw_all_in_one_create_privacy_pages');
			form_data.append('security', lw_all_in_one_admin_ajax_object.security);
			$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: form_data,
					contentType: false,
					cache: false,
					processData: false
				})
				.done(function (response) {
					if (response.success === true) {
						var createdPages = response.data;
						$.each(createdPages, function (index, value) {
							if (value.status === 'success') {
								$('#created_pages_response').append('<div class="notice notice-success inline"><p>' + value.message + '</p></div>');
							} else {
								$('#created_pages_response').append('<div class="notice notice-error inline"><p>' + value.message + '</p></div>');
							}
						});
					} else {
						$('#created_pages_response').append('<div class="notice notice-error inline"><p>__( \'Something went wrong with the request!\', \'lw_all_in_one\' )</p></div>');
					}
				});
		});
		$(document).on('click', '#wim_verify_attivation', function (event) {
			event.preventDefault();
			var form_data = new FormData();
			form_data.append('action', 'lw_all_in_one_verify_wim_attivation');
			form_data.append('security', lw_all_in_one_admin_ajax_object.security);
			$.ajax({
					url: ajaxurl,
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
						$('#wim_fields_token_resp').val(response.data['token']);
						window.setTimeout(
							document.getElementById("lw_all_in_one_options").submit.click(),
							3000
						);
					} else {
						$('#verification_status_response').append('<div class="notice notice-error inline"><p>' + response.data + '</p></div>');
					}
				});
		});

		$(document).on('click', '#ga_add_custom_event_button', function (event) {
			event.preventDefault();
			var ga_custom_event_name = $('#ga_custom_event_name').val();
			var ga_custom_event_selector = $('#ga_custom_event_selector').val();
			var ga_custom_event_cat = $('#ga_custom_event_cat').val();
			var ga_custom_event_act = $('#ga_custom_event_act').val();
			var ga_custom_event_lab = $('#ga_custom_event_lab').val();
			var form_data = {
				action: 'lw_all_in_one_add_ga_event',
				security: lw_all_in_one_admin_ajax_object.security,
				ga_custom_event_name,
				ga_custom_event_selector,
				ga_custom_event_cat,
				ga_custom_event_act,
				ga_custom_event_lab
			};

			$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: form_data,
					cache: false,
					processData: true
				})
				.done(function (response) {
					if (response.success === true) {
						$("#ga_events_table tbody").append(`
						<tr>
							<td colspan="2" class="lw-aio-settings-title">
								<div class="button-secondary lw-aio-settings-custom-switch">
									<input type="checkbox" name="lw_all_in_one[ga_fields][ga_custom_event][${response.data['event_id']}]" class="lw-aio-settings-custom-switch-checkbox" id="ga_custom_event_${response.data['event_id']}">
									<label class="lw-aio-settings-custom-switch-label" for="ga_custom_event_${response.data['event_id']}">
										<div class="lw-aio-settings-custom-switch-inner"></div>
										<div class="lw-aio-settings-custom-switch-switch"></div>
									</label>
								</div>
								<div class="switch-desc"> Track ${ga_custom_event_name}</div>
							</td>
						</tr>
					`);
						addEventDisplayMessage('success', response.data['message']);
					} else {
						addEventDisplayMessage('error', response.data['message']);
					}
				});
		});

		$(document).on('click', '.remove_custom_event', function (event) {
			event.preventDefault();
			var tr = $(this).parent().parent();
			var ga_event_id = $(this).attr('ga-event-id');
			var form_data = {
				action: 'lw_all_in_one_remove_ga_event',
				security: lw_all_in_one_admin_ajax_object.security,
				ga_event_id,
			};
			$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: form_data,
					cache: false,
					processData: true
				})
				.done(function (response) {
					if (response.success === true) {
						$(tr).remove();
					}
				});
		});

		$(document).on('click', '#lw_aio_delete_record', function (event) {
			if (!confirm(__( 'Are you sure you want to delete this record?', 'lw_all_in_one' ))) {
				event.preventDefault();
			}
		});
		$("#lw_aio_saved_cf7_records, #lw_aio_saved_ga_events_records").submit(function (event) {
			var actionTop = $("select[name=action]").val();
			var actionBottom = $("select[name=action2]").val();
			if ((actionTop == 'bulk-delete-cf7' || actionTop == 'bulk-delete-ga') || (actionBottom == 'bulk-delete-cf7' || actionBottom == 'bulk-delete-ga')) {
				if (!confirm(__( 'Are you sure you want to delete this record?', 'lw_all_in_one' ))) {
					event.preventDefault();
				}
			}
		});

		$(document).on('click', '#lw_aio_reset_data', function (event) {
			event.preventDefault();
			if (confirm(__( 'Are you sure you want to reset plugin options?', 'lw_all_in_one' ))) {
				var form_data = {
					action: 'lw_all_in_one_reset_plugin_options',
					security: lw_all_in_one_admin_ajax_object.security,
				};
				$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: form_data,
						cache: false,
						processData: true
					})
					.done(function (response) {
						if (response.success === true) {
							location.replace('/wp-admin/admin.php?page=lw_all_in_one');
						}
					});
			}
		});
	});
})(jQuery);
