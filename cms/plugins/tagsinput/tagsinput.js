cms.init.add(['page_add', 'page_edit'], function() {
	$('#pageEditMetaTagsField').tagsInput({
		width: 'auto',
		defaultText: __('add a tag'),
		autocomplete_url:'/ajax-tags-get'
	});
})