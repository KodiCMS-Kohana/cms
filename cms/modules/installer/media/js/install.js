// Auto generated i18n lang file for lang ru-ru
cms.addTranslation({
	'Finish': 'Установить',
	'Next': 'Далее',
	'Previous': 'Назад',
	'Loading ...': 'Загрузка ...',
	'Before proceeding to the next step you need to fix errors': 'Прежде чем приступить к следующему шагу вы должны исправить ошибки'
});

$(function() {
	$('#wizard').on('change', '#current-lang', function() {
		window.location = '/install/index?lang=' + $(this).val();
	})
	
	var password_generator_status = function() {
		var checkbox = $('#generate-password-checkbox');

		if(checkbox.is(':checked')){
			$('#password-form').hide();
		} else {
			$('#password-form').show();
		}
	}
	
	$('#wizard').on('change', '#generate-password-checkbox', password_generator_status);
	password_generator_status();
	
	function show_error($error) {
		$('#wizard .wizard-alert').remove();

		$('#wizard .body.current .widget-content')
			.append('<p class="wizard-alert alert alert-error">'+$error+'</p>');
	}
	
	$("#wizard").steps({
		labels: {
			current: "current step:",
			pagination: "Pagination",
			finish: __("Finish"),
			next: __("Next"),
			previous: __("Previous"),
			loading: __("Loading ...")
		},
		onStepChanging: function (event, currentIndex, newIndex) {
			if(currentIndex == 1 && newIndex > currentIndex && failed) {
				parse_messages([__('Before proceeding to the next step you need to fix errors')], 'error');
				return false;
			}
			
			if(currentIndex == 2 && newIndex > currentIndex) {
				return check_connect();
			}
	
			return true;
		},
		onFinishing: function (event, currentIndex) {
			return $("form").submit();
		}
	});
	
	function check_connect() {
		cms.clear_error();
		var $fields = $(':input[name*=db_]').serialize();
		var response = $.ajax({
			type: "POST",
			url: "check_connect",
			data: $fields,
			async: false,
			dataType: 'json'
		}).responseJSON;
	
		if(response.status === true) return response.status;
		
		if(response.message) {
			cms.clear_error();
			parse_messages(response.message, 'error');
		}
		
		return false;
	}
	
	$('.select2-container').remove();
	cms.ui.init('select2');
})

