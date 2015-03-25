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
		window.location = '?lang=' + $(this).val();
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

		$('#wizard .wizard-pane.active .widget-content')
			.append('<p class="wizard-alert alert alert-error">'+$error+'</p>');
	}
	
	$("#wizard").steps({
		labels: {
			current: "",
			pagination: "Pagination",
			finish: __("Finish"),
			next: __("Next"),
			previous: __("Previous"),
			loading: __("Loading ...")
		},
		onStepChanging: function (event, currentIndex, newIndex) {
			$form = $(".form-horizontal");

			if(currentIndex == 1 && newIndex > currentIndex && failed ) {
				cms.messages.parse([__('Before proceeding to the next step you need to fix errors')], 'error');
				return false;
			}
			
			if(currentIndex == 2 && newIndex > currentIndex) {
				if(validate_step_2($form))
				{
					return check_connect();
				}
				
				return false;
			}
			
			if(currentIndex == 3 && newIndex > currentIndex) {
				return validate_step_3($form);
			}
	
			return true;
		},
		onFinishing: function (event, currentIndex) {
			return $("form").submit();
		}
	});
	
	function validate_step_2($form) {
		$form.validate({
			onsubmit: false,
			rules: {
				'install[db_server]': "required", 
				'install[db_port]': "required",
				'install[db_user]': "required",
				'install[db_name]': "required"
			}
		}, true);
		
		return $form.valid();
	}
	
	function validate_step_3($form) {

		$form.validate({
			onsubmit: false,
			rules: {
				'install[site_name]': "required", 
				'install[username]': "required",
				'install[password_field]': {
					required: true,
					minlength: 5
				},
				'install[email]': {
					required: true,
					email: true
				},
				'install[admin_dir_name]': "required", 
				'install[password_confirm]': {
					equalTo: "#password"
				},
			}
		}, true);

		return $form.valid();
	}

	function check_connect() {
		cms.clear_error();
		var $fields = $(':input[name*=db_]').serialize();
		
		var response = Api.get('install.check_connect', $fields, false, false);
	
		if(response.status === true) return response.status;
		
		if(response.message) {
			cms.clear_error();
			cms.messages.parse(response.message, 'error');
		}
		
		return false;
	}
	
	connection_container_visible();
	function connection_container_visible() {
		var $cont = $('.connection-settings');
		switch ($('#database-driver').val()) {
			case 'pdo::sqlite':
				$cont.slideUp();
				break;
			default:
				$cont.slideDown();
		}
		
		$('#database-driver').on('change', connection_container_visible);
	}
	
	$('.select2-container').remove();
	cms.ui.init('select2');
	cms.ui.init('icon');
});

