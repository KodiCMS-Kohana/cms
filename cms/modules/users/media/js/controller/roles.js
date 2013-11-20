cms.init.add(['roles_edit', 'roles_add'], function () {
	$('.widget').on('click', 'label.check_all', function() {
		var $list = $(this)
			.parent()
			.parent()
			.parent()
			.parent()
			.find('tbody input')
			.check();
	});
});