<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<h1><?php echo __('Forgot password'); ?></h1>

<?php echo Form::open( Route::url( 'user', array('action' => 'forgot') ), array('method' => 'post') ); ?>
	<?php echo Form::hidden( 'forgot[seturity_token]', Security::token() ); ?>
	<div class="control-group">
		<label class="control-label" for="forgotEmailField"><?php echo __('E-mail address'); ?>:</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-envelope"></i></span><input class="login-field" type="text" id="forgotEmailField" name="forgot[email]" value="<?php echo $email; ?>">
			</div>
		</div>
	</div>

	<?php Observer::notify('admin_login_forgot_form'); ?>
	
	<div class="login-actions">
		<a href="/login/" class="btn pull-left">
			<i class="icon-chevron-left"></i>
			<?php echo __('Login'); ?>
		</a>
		<button class="btn btn-large pull-right">
			<?php echo __('Send password'); ?>
			<i class="icon-chevron-right"></i>
		</button>
	</div>
	
	<div class="clearfix"></div>
</form>