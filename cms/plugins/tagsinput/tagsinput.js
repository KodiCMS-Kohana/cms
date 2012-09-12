cms.init.add(['page_add', 'page_edit'], function() {
	$('#pageEditMetaTagsField').tagsInput({
		width: 'auto',
		'delimiter': TAG_SEPARATOR,
		defaultText: __('add a tag'),
		autocomplete_url:'/ajax-tags-get'
	});
})