<div class="hero-unit" id="login-form">
	<h1><?php echo Setting::get('admin_title'); ?></h1>
	
	<hr />
	
	<?php if(  is_array( $install_data)): ?>
	<div class="alert alert-info">
		<h5><?php echo __('CMS succefully installed'); ?></h5>
		<ul>
			<li>Login: <?php echo Arr::get($install_data, 'username'); ?></li>
			<li>Password: <?php echo Arr::get($install_data, 'password'); ?></li>
		</ul>
	</div>
	<?php endif; ?>

	<?php echo Form::open( Route::url( 'user', array('action' => 'login') ), array(
		'method' => 'post', 'class' => 'form-vertical'
	) ); ?>

	<?php echo Form::hidden( 'token', Security::token() ); ?>

	<div class="control-group">
		<div class="input-prepend">
			<span class="add-on"><i class="icon-user"></i></span>
			<input class="login-field" type="text" id="username" name="login[username]" value="">
		</div>
	</div>

	<div class="control-group">
		<div class="input-prepend  input-append">
			<span class="add-on">
				<i class="icon-lock"></i>
			</span>
			<input class="login-field" type="password" id="password" name="login[password]" value="">
			<?php echo HTML::anchor(ADMIN_DIR_NAME . '/login/forgot', __('Forgot password?'), array('class' => 'btn btn-large btn-link')); ?>
		</div>
	</div>

	<?php Observer::notify('admin_login_form'); ?>

	<div class="control-group">
		<label class="checkbox">
			<input name="login[remember]" type="checkbox" value="checked" tabindex="4">
			<?php echo __('Remember me for 14 days'); ?>
		</label>
	</div>
	
	<hr />

	<?php echo Form::button('sign-in', __('Login') . '<i class="icon-chevron-right"></i>', array(
		'class' => 'btn btn-large'
	)); ?>

<?php echo Form::close(); ?>
</div>