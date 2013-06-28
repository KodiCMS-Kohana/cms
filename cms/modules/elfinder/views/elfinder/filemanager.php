<script>
	$(function() {
		var elfinder = $('#elfinder').elfinder({
			lang: 'ru',
			url : '/api-elfinder',
            resizable: false,
			height: 600
		}).elfinder('instance');
	});
</script>

<div id="elfinder"></div>