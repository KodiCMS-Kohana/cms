cms.init.add('setting_index', function () {
	$('#clear-cache').on('click', function() {
		Api.get('cache.clear', {}, function() {

		});
		return false;
	});
	
	
	$('#content .widget-header').each(function(i) {
		$('<li><a href="#tab' + i + '" data-toggle="tab">' + $(this).text() + '</a></li>').appendTo($('.tabbable .nav'));
		$('<div class="tab-pane" id="tab' + i + '"><h2>'+$(this).text()+'</h2><hr />' + $(this).next().html() + '</div>').appendTo($('.tabbable .tab-content'));

		$(this).next().remove();
		$(this).remove();
	});
	
	$('.tabbable .nav li:first-child').addClass('active');
	$('.tabbable .tab-pane:first-child').addClass('active');
	
	$('.tabbable .tab-pane').css({
		'min-height': $('.tabbable .nav').height()
	})
});