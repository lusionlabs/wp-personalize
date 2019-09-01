jQuery(document).ready(function($) {
	$.ajaxSetup ({cache: false});

	$('.btn-update-settings').click(function() {
		$('.wpp-section-settings-container').block({message: null});

		var postData = {
				'action'	 : 'wpp_update_settings',
				'location'	 : {},
				'type'		 : {},
				'area'		 : {},
				'ajax_nonce' : wpPersonalize.ajax_nonce
			};

		$('.check-location').each(function(i){
			postData['location'][$(this).val()] = $(this).is(':checked');
		});

		$('.check-type').each(function(i){
			postData['type'][$(this).val()] = $(this).is(':checked');
		});

		$('.check-area').each(function(i){
			postData['area'][$(this).val()] = $(this).is(':checked');
		});

		$.post(ajaxurl, postData, function(response) {
			response = response.split('{"');
			response = '{"' + response[1];
			var parsedData = JSON.parse(response);

			setTimeout(function() {
				$('.wpp-section-settings-container').unblock();
			}, 1000);
		});
	});

});

jQuery(window).load(function($) {

});

jQuery(window).resize(function($) {

});