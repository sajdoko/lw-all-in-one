(function ($) {
	'use strict';
	var lwAioRedirectLink;
	var lwAioRedirectCalled = true;
	var lwDefaultPrevented = false;
	// duhet kontrolluar nese eshte Gtag apo analytics i thjeshte
	var optionsGaWithGtag = true;
	var optionsEventBouncerateg = false;

	function saveGaEvent(gaCategory, gaAction, gaLabel) {
		if (lwAioSaveGaEvents === false){
			return;
		}

		var data = {
			action: 'lw_all_in_one_save_ga_event',
			security: lw_all_in_one_save_ga_event_object.security,
			event_category: gaCategory,
			event_action: gaAction,
			event_label: gaLabel
		};
		jQuery.post(lw_all_in_one_save_ga_event_object.ajaxurl, data, function(response) {
			// console.log(response);
		});
	}

	function lwAioRedirect() {
		if (lwAioRedirectCalled) {
			return;
		}
		lwAioRedirectCalled = true;
		if (lwDefaultPrevented == false) {
			document.location.href = lwAioRedirectLink;
		} else {
			lwDefaultPrevented = false;
		}
	}

	function lwAioSendEvent(gaCategory, gaAction, gaLabel, d) {
		if (optionsGaWithGtag) {
			if (d) {
				if (optionsEventBouncerateg) {
					gtag("event", gaAction, {
						event_category: gaCategory,
						event_label: gaLabel,
						non_interaction: true,
						event_callback: lwAioRedirect
					});
				} else {
					gtag("event", gaAction, {
						event_category: gaCategory,
						event_label: gaLabel,
						event_callback: lwAioRedirect
					});
				}
			} else {
				if (optionsEventBouncerateg) {
					gtag("event", gaAction, {
						event_category: gaCategory,
						event_label: gaLabel,
						non_interaction: true
					});
				} else {
					gtag("event", gaAction, {
						event_category: gaCategory,
						event_label: gaLabel
					});
				}
			}
		} else {
			if (d) {
				if (optionsEventBouncerateg) {
					ga("send", "event", gaCategory, gaAction, gaLabel, {
						nonInteraction: true,
						hitCallback: lwAioRedirect
					});
				} else {
					ga("send", "event", gaCategory, gaAction, gaLabel, {
						hitCallback: lwAioRedirect
					});
				}
			} else {
				if (optionsEventBouncerateg) {
					ga("send", "event", gaCategory, gaAction, gaLabel, {
						nonInteraction: true
					});
				} else {
					ga("send", "event", gaCategory, gaAction, gaLabel);
				}
			}
		}
	}
	$(window).on("load", function () {
		if (lwAioGaActivate) {
			if (lwAioMonitorEmailLink) {
				$('a[href^="mailto"]').click(function () {
					var gaCategory = this.getAttribute("data-vars-ga-category") || "email";
					var gaAction = this.getAttribute("data-vars-ga-action") || "send";
					var gaLabel = this.getAttribute("data-vars-ga-label") || this.href;
					lwAioSendEvent(gaCategory, gaAction, gaLabel, true);
					saveGaEvent(gaCategory, gaAction, gaLabel);
				});
			}
			if (lwAioMonitorTelLink) {
				$('a[href^="tel"]').click(function () {
					var gaCategory = this.getAttribute("data-vars-ga-category") || "telephone";
					var gaAction = this.getAttribute("data-vars-ga-action") || "call";
					var gaLabel = this.getAttribute("data-vars-ga-label") || this.href;
					lwAioSendEvent(gaCategory, gaAction, gaLabel, true);
					saveGaEvent(gaCategory, gaAction, gaLabel);
				});
			}
			if (lwAioMonitorFormSubmit) {
				$(".wpcf7").on( 'wpcf7:mailsent', function( event ){
					// console.log(event);
					// return;
					var gaCategory = "form";
					var gaAction = "submit";
					var gaLabel = event.currentTarget.baseURI;
					lwAioSendEvent(gaCategory, gaAction, gaLabel, true);
					saveGaEvent(gaCategory, gaAction, gaLabel);
				});
			}
			if (lwAioMonitorCustomEvents) {
				$.each(lwAioCustomEvents, function( i, v ) {
					$( v.ga_custom_event_selector).click(function () {
						var gaCategory = v.ga_custom_event_cat || "custom_event_cat" + i;
						var gaAction = v.ga_custom_event_act || "custom_event_act" + i;
						var gaLabel = v.ga_custom_event_lab || "custom_event_lab" + i;
						lwAioSendEvent(gaCategory, gaAction, gaLabel, true);
						saveGaEvent(gaCategory, gaAction, gaLabel);
					});
				});
			}
		}
	});
	$( window ).load(function() {
		if ($('[id="rag_soc"]').length > 1) {
			console.log('wim_twice');
		}
	});

})(jQuery);
