(function ($) {
	"use strict";

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	// $( window ).load(function() {
	// 		$(".tab-content").each(function (key, value) {
	// 			if (key === 0) {
	// 				$(this).show();
	// 			} else {
	// 				$(this).hide();
	// 			}
	// 		});
	// 		$(".nav-tab-wrapper a").each(function (key, value) {
	// 			if (key === 0) {
	// 				$(this).addClass("nav-tab-active");
	// 			}
	// 		});
	// });
	// $(document).on('click', '.nav-tab', function () {
	// 	if (window.location.href.indexOf("page=lw_all_in_one") != -1) {
	// 		$(".nav-tab-wrapper a").each(function () {
	// 			$(this).removeClass("nav-tab-active");
	// 		});
	// 		$(this).addClass("nav-tab-active");
	// 		$(".tab-content").each(function () {
	// 			$(this).hide();
	// 		});
	// 		$($(this).attr('href')).show();
	// 	}
	// });
	$(document).ready(function () {
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
									$('#created_pages_response').append('<div class="notice notice-error inline"><p>Something went wrong with the request!</p></div>');
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
									$('#verification_status_response').append('<div class="notice notice-success inline"><p>' + response.data + '</p></div>');
                } else {
									$('#verification_status_response').append('<div class="notice notice-error inline"><p>' + response.data + '</p></div>');
                }
            });
    });
	});
})(jQuery);
