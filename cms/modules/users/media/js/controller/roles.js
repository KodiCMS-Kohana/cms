cms.init.add(['roles_edit', 'roles_add'], function () {
	$('.widget').on('change', 'input[name=check_all]', function() {
		var $list = $(this)
			.parent()
			.parent()
			.parent()
			.parent()
			.find('tbody input');

		if(!$(this).is(':checked')) {
			$list.uncheck();
		} else {
			$list.check();
		}
	});
});