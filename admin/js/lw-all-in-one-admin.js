(function ($) {
	"use strict";
	const {
		__,
		_x,
		_n,
		_nx
	} = wp.i18n;
	$(document).ready(function () {
		function addEventDisplayMessage(type, message) {
			$('#add_event_response').html(`<p class="notice notice-${type}">${message}</p>`);
			setTimeout(function () {
				$('#add_event_response').html('')
			}, 3000);
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
						$('#wim_fields_token_resp').val(response.data.fields['token']);
						$('#wim_fields_auto_show_wim').val(response.data.fields['auto_show_wim']);
						$('#wim_fields_show_wim_after').val(response.data.fields['show_wim_after']);
						$('#wim_fields_show_mobile').val(response.data.fields['show_mobile']);
						$('#wim_fields_lingua').val(response.data.fields['lingua']);
						$('#wim_fields_messaggio_0').val(response.data.fields['messaggio_0']);
						$('#wim_fields_messaggio_1').val(response.data.fields['messaggio_1']);
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
			if (!confirm(__('Are you sure you want to delete this record?', 'lw_all_in_one'))) {
				event.preventDefault();
			}
		});
		$("#lw_aio_saved_cf7_records, #lw_aio_saved_ga_events_records").submit(function (event) {
			var actionTop = $("select[name=action]").val();
			var actionBottom = $("select[name=action2]").val();
			if ((actionTop == 'bulk-delete-cf7' || actionTop == 'bulk-delete-ga') || (actionBottom == 'bulk-delete-cf7' || actionBottom == 'bulk-delete-ga')) {
				if (!confirm(__('Are you sure you want to delete this record?', 'lw_all_in_one'))) {
					event.preventDefault();
				}
			}
		});

		$(document).on('click', '#lw_aio_reset_data', function (event) {
			event.preventDefault();
			if (confirm(__('Are you sure you want to reset plugin options?', 'lw_all_in_one'))) {
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

		$(document).on('click', '#lw_aio_purify_css', function (event) {
			event.preventDefault();
			$('#lw_aio_spinner').addClass('is-active');
			$('#lw_aio_purify_css').attr('disabled', 'disabled');
			$('#lw_aio_purify_css').click(false);
			var form_data = {
				action: 'lw_all_in_one_purify_css',
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
					$('#lw_aio_spinner').removeClass('is-active');
					$('#lw_aio_purify_css').attr('disabled', '');
					$('#lw_aio_purify_css').click(true);
					if (response.success === true) {
						showPurifyResults(response.data);
					}
				});
		});

		function showPurifyResults(data) {
			var html = "";
			var totalBeforeBytes = 0;
			var totalAfterBytes = 0;
			var css_minified = data.css_minified;

			if (typeof css_minified === 'object' && css_minified !== null) {
				$.each(css_minified, function (index, item) {
					totalBeforeBytes += item.stats.beforeBytes;
					totalAfterBytes += item.stats.afterBytes;
					html += "<div class='css-item'>" +
						"<a href='" + item.url + "' target='_blank'>" + item.url + "</a><br/>" +
						"<div class='stats'><div class='tab'>" + __("before", 'lw_all_in_one') + ": <span class='before'>" + item.stats.before + "</span></div>" +
						"<div class='tab'>" + __("after", 'lw_all_in_one') + ": <span class='before'>" + item.stats.after + "</span></div>" +
						// "<div class='tab'>used: <span class='before'>" + item.stats.percentageUsed + "</span></div>" +
						// "<div class='tab'>unused: <span class='before'>" + item.stats.percentageUnused + "</span></div>" +
						"</div></div>";
				});
				var percentageUnused = Math.round((1 - totalAfterBytes / totalBeforeBytes) * 10000) / 100 + "%";
				$('.total').append(formatBytes(totalBeforeBytes - totalAfterBytes) + " (" + percentageUnused + ") " + __("of your CSS was not used.", 'lw_all_in_one'));
				$('.css-items').append(html);
			} else {
				$('.total').append(__("No stylesheets purified!", 'lw_all_in_one'));
			}

			$('.results').show();

		}

		function formatBytes(bytes) {
			var sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
			if (bytes === 0) return '0 Byte';
			var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
			return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
		};

		$(document).on('click', '.restore-purified', function (event) {
			event.preventDefault();
			var element = $(this);
			$(element).click(false);
			$(element).attr('disabled', 'disabled');
			$(element).parent().append('<div id="lw_aio_spinner_restore" class="spinner is-active" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;">');
			var form_data = {
				action: 'lw_all_in_one_restore_purified',
				security: lw_all_in_one_admin_ajax_object.security,
				file_id: $(this).attr("file-id"),
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
						$(element).parent().html(__("Restored!", 'lw_all_in_one'));
					} else {
						$(element).click(true);
						$(element).attr('disabled', '');
						$('#lw_aio_spinner_restore').remove();
					}
				});
		});

	});
})(jQuery);
