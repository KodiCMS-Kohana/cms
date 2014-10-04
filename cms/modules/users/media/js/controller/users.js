cms.init.add(['users_edit', 'users_add'], function () {
	$('input[name="user_roles"]').select2({
		placeholder: __("Click to get list of roles"),
		minimumInputLength: 0,
		multiple: true,
		ajax: {
			url: Api.build_url('user-roles.get'),
			data: function(query, pageNumber, context) {
				return {
					key: query,
					fields: 'id,name'
				}
			},
			dataType: 'json',
			results: function (resp, page) {
				var roles = [];
				if(resp.response) {
					for(i in resp.response) {
						roles.push({
							id: resp.response[i]['id'],
							text: resp.response[i]['name']
						});
					}
				}

				return {results: roles};
			}
		},
		initSelection: function(element, callback) {
			element.val('');
			if (!USER_ID) {
				callback([{'id':1, 'text':'login'}]);
				return ;
			}

			Api.get('users.roles', {
					uid: USER_ID,
					fields: 'id,name'
			}, function(resp, page) {
				var roles = [];
				if(resp.response) {
					for(i in resp.response) {
						roles.push({
							id: resp.response[i]['id'],
							text: resp.response[i]['name']
						});
					}
				}

				callback(roles);
			});
		}
	});
});

cms.init.add('users_profile', function () {
	var toolbar = $('.profile-toolbar');
	var toolbar_l = toolbar.text().replace(/\t/g, '').replace(/\n/g, '').replace(/&nbsp;/g, '').replace(/ /g, '').length;
	
	if(!toolbar_l) toolbar.css({'padding': 0});
});

cms.init.add('users_edit', function () {
	$('#themes .theme').on('click', function (e) {
		if ($(this).hasClass('active'))
			e.preventDefault();

		$('#themes .active').removeClass('active');
		$(this).addClass('active');

		activateTheme($(this).data('theme'));
		e.preventDefault();
	});
});


var activateTheme = function(theme) {
	Api.post('user-meta', {key: 'admin_theme', value: theme});
	document.body.className = document.body.className.replace(/theme\-[a-z0-9\-\_]+/ig, 'theme-' + theme);
}