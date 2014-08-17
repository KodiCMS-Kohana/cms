cms.init.add(['roles_edit', 'roles_add'], function () {
	$('.panel').on('click', '.check_all', function(e) {
		var $list = $(this)
			.closest('table')
			.find('input')
			.check();

		e.preventDefault();
	});
});