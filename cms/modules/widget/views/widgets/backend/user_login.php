<div class="panel-body ">
	<div class="form-group">
		<label class="control-label col-md-3" for="login_field"><?php echo __('Login ID (POST)'); ?></label>
		<div class="col-md-3">
			<?php echo Form::input('login_field', $widget->get('login_field'), array(
				'class' => 'form-control', 'id' => 'login_field'
			)); ?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3" for="password_field"><?php echo __('Password ID (POST)'); ?></label>
		<div class="col-md-3">
			<?php echo Form::input('password_field', $widget->get('password_field'), array(
				'class' => 'form-control', 'id' => 'password_field'
			)); ?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3" for="next_url"><?php echo __('Next page by default (URI)'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('next_url', $widget->get('next_url'), array(
				'class' => 'form-control', 'id' => 'next_url'
			)); ?>
		</div>
	</div>

	<hr class="panel-wide" />
	
	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label><?php echo Form::checkbox('remember', 1, $widget->remember == 1); ?> <?php echo __('Allow Autologin'); ?></label>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3" for="remember_field"><?php echo __('Autologin ID (POST)'); ?></label>
		<div class="col-md-3">
			<?php echo Form::input('remember_field', $widget->get('remember_field'), array(
				'class' => 'form-control', 'id' => 'remember_field'
			)); ?>
		</div>
	</div>
</div>
<script>
$(function() {
	$('button[name="new_rule"]').on('click', function() {
		var $cont = $('.roles-redirect-contaier');
		var $item = $('.roles-redirect-item:last-child');
		var $key = $('.roles-redirect-item').length;
		
		$item
			.clone()
			.find('.select2-container')
				.remove()
				.end()
			.find('select')
				.attr('name', 'roles_redirect[' + $key + '][roles][]')
				.find('option:selected')
					.removeAttr('selected')
					.end()
				.select2()
				.end()
			.find('input')
				.val('')
				.attr('name', 'roles_redirect[' + $key + '][next_url]')
				.end()
			.appendTo($cont);
		return false;
	});
});
</script>
<div class="panel-heading" data-icon="share">
	<span class="panel-title"><?php echo __('User redirect rules'); ?></span>
</div>

<div class="panel-body">
	<div class="roles-redirect-contaier">
		<?php foreach($widget->roles_redirect as $key => $data): ?>
		<div class="roles-redirect-item">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<div class="form-group">
				<label class="control-label col-md-3"><?php echo __('Roles'); ?></label>
				<div class="col-md-4">
					<?php echo Form::select('roles_redirect['.$key.'][roles][]',  $roles, Arr::get($data, 'roles', array())); ?>	
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-md-3" for="next_url"><?php echo __('Next page (URI)'); ?></label>
				<div class="col-md-9">
					<?php echo Form::input('roles_redirect['.$key.'][next_url]', Arr::get($data, 'next_url'), array(
						'class' => 'form-control'
					)); ?>
				</div>
			</div>
			
			<hr class="panel-wide" />
		</div>
		<?php endforeach; ?>
	</div>
	
	<div class="col-md-offset-3">
		<button name="new_rule" class="btn btn-default" data-icon="plus"><?php echo __('Add new rule'); ?></button>
	</div>
</div>