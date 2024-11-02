var bolPP = bolPP || {};

bolPP.main = (function($) {
    var methods = {
        init : function() {
			$.fn.ready(function() {
				methods.requestProducts();
			});
        },
        requestProducts : function() {
			var postIdRaw = $('body').prop('class').match(/\s\w+id-(\d+)\W/),
				postId = 0,
				$resultEl = $('.js--bol-pp-results');

			if (postIdRaw.length) {
				postId = postIdRaw[1];
				$.ajax({
					type: "GET",
					url: $resultEl.data('url'),
					data: ({'action':'bol_request', post_id : postId}),
					success: function(data) {
						$('.js--bol-pp-results').html(data);
					}
				});

			}
        }
    };

	if ($) {
		methods.init();
		return {
			requestProducts : function() {
				methods.requestProducts();
			}
		};
	} else {
		console.warn('Sorry, jQuery is required');
	}
})(jQuery);
