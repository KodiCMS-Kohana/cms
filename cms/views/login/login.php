<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<?php echo Form::open( Route::url( 'user', array('action' => 'login') ), array('method' => 'post') ); ?>
	<?php echo Form::hidden( 'login[seturity_token]', Security::token() ); ?>
	<h1><?php echo Setting::get('admin_title'); ?></h1>

	<div class="control-group">
		<label class="control-label" for="username"><?php echo __('Username'); ?>:</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-user"></i></span><input class="login-field" type="text" id="username" name="login[username]" value="">
			</div>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="password"><?php echo __('Password'); ?>:</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-lock"></i></span><input class="login-field" type="password" id="password" name="login[password]" value="">
			</div>
		</div>
	</div>

	<?php Observer::notify('admin_login_form'); ?>

	<div class="login-actions">
		<div class="control-group pull-left">
			<div class="controls">
				<label class="checkbox">
					<input name="login[remember]" type="checkbox" value="checked" tabindex="4">
					<?php echo __('Remember me for 14 days'); ?>
				</label>
			</div>
		</div>

		<button class="btn btn-large pull-right">
			<?php echo __('Login'); ?>
			<i class="icon-chevron-right"></i>
		</button>
	</div> <!-- .actions -->
	
	<div class="clearfix"></div>
	<!-- Text Under Box -->
	<div class="login-extra">
		<a href="/admin/login/forgot"><?php echo __('Forgot password?'); ?></a>
	</div> <!-- /login-extra -->
</form>


