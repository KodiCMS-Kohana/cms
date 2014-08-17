<script type="text/javascript">
$(function(){
	$('body').on('click', '#refresh-api-key', function() {
		Api.post('api.refresh', {key: $('#api-key').val()}, function(response) {
			if(response.response)
				$('#api-key').val(response.response);
		});
		
		return false;
	});
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
				<?php if( ACL::check('system.api.refresh')): ?>
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
			<?php echo Form::select( 'setting[api][mode]', Form::choices(), Config::get('api', 'mode')); ?>
		</div>
	</div>
</div>