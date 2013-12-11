cms.ui.add('tags_input', function() {
	$(':input.tags').tagsInput({
		width: 'auto',
		delimiter: TAG_SEPARATOR,
		defaultText: __('Add a tag'),
		autocomplete_url:'/api-tags',
		autocomplete: {
			source: function( request, response ) {
				$.getJSON( '/api-tags', {term: request.term}, function(data) {
					response(data.response)
				});
			}
		}
	});
});