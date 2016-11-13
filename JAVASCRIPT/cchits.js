(function() {
	$(document).ready(function() {
		console.log("CCHits.net, the site where you make the charts. Let's go !");
		$('.admin-tab-button').click(function(event) {
			event.preventDefault();	
			$('.admin-tab-button').parents('li').removeClass('active');
			$(this).parent('li').addClass('active');
			var target = $(this).attr('data-target');
			$('.admin-panel').hide();
			$(target).show();
		});
	});
}());
