<script type="text/javascript">
$(function(){
	$('body').on('click', '#refresh-api-key', function() {
		Api.post('api.refresh', {key: $('#api-key').val()}, function(response) {
			if(response.response)
				$('#api-key').val(response.response);
		});

		return false;
	});

	
	var keys = Api.get('api.keys', {}, function(response) {
		var $container = $('#api-keys').removeClass('hidden');

		$container.on('click', '.add-row', function(e) {
			e.preventDefault();
			var $description = prompt("Please enter key description");
			
			if(!$description) return;
	
			Api.put('api.key', {description: $description}, function(response) {
				if(response.response) {
					var $row = clone_row($container);
					fill_row($row, response.response, $description);
				}
			});
		});

		$container.on('click', '.remove-row', function(e) {
			var $cont = $(this).closest('.row-helper');
			Api.delete('api.key', {key: $cont.data('id')}, function(response) {
				if(response.response)
					$cont.remove();
			});
			e.preventDefault();
		});
		
		for(key in response.response) {
			var row = clone_row($container);
			fill_row(row, key, response.response[key]);
		}
	});
	
	function fill_row($row, $key, $description) {
		var input = $row.find('.row-value');
		
		$row.find('.api-key').text($key);
		input.val($description);
		$row.data('id', $key);
	}
	
	function clone_row($container) {
		return $('.row-helper.hidden', $container)
			.clone()
			.removeClass('hidden')
			.appendTo($('.rows-container', $container))
			.find(':input')
			.end();
	}
});
</script>

<div class="panel-heading" data-icon="flask">
	<span class="panel-title"><?php echo __('API'); ?></span>
</div>
<div class="panel-body api-settings">
	<div class="form-group">
		<label class="control-label col-lg-3"><?php echo __('KodiCMS API key'); ?></label>
		<div class="col-lg-7">
			<div class="input-group">
				<?php echo Form::input(NULL, Config::get('api', 'key'), array(
					'id' => 'api-key', 'class' => 'form-control', 'readonly'
				)); ?>
				<?php if (ACL::check('system.api.refresh')): ?>
				<div class="input-group-btn">
					<?php echo HTML::anchor('#', __('Change key'), array(
						'class' => 'btn btn-primary', 'id' => 'refresh-api-key', 'data-icon' => 'refresh'
					)); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<hr class="panel-wide" />

	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('API enable'); ?></label>
		<div class="col-md-2">
			<?php echo Form::select('setting[api][mode]', Form::choices(), Config::get('api', 'mode')); ?>
		</div>
	</div>
	
	<hr class="panel-wide" />

	<div class="form-group hidden" id="api-keys">
		<label class="control-label col-md-3"><?php echo __('API keys'); ?></label>
		<div class="col-xs-9">
			<div class="row-helper hidden padding-xs-vr">
				<div class="input-group">
					<span class="input-group-addon api-key bg-success"></span>
					<?php echo Form::input(NULL, NULL, array(
						'disabled', 'class' => 'row-value form-control',
					)); ?>
					<div class="input-group-btn">
						<?php echo Form::button('trash-row', UI::icon('trash-o'), array(
							'class' => 'btn btn-warning remove-row'
						)); ?>
					</div>
				</div>
			</div>

			<div class="rows-container"></div>

			<?php echo Form::button('add-row', UI::icon('plus'), array(
				'class' => 'add-row btn btn-primary', 'data-hotkeys' => 'ctrl+a'
			)); ?>
		</div>
	</div>
</div>