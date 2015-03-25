<div class="frontend-header">
	<a href="/" class="logo">
		<?php echo HTML::image( ADMIN_RESOURCES . 'images/logo-color.png'); ?>
	</a>

	<?php echo HTML::anchor(Route::get('user')->uri(array(
		'action' => 'login'
	)), __('Login'), array(
		'class' => 'btn btn-primary'
	)); ?>
	
	<div class="clearfix"></div>
</div>

<div class="page-signin-alt">
	<?php echo Form::open(Route::get('user')->uri(array('action' => 'forgot')), array('method' => Request::POST, 'class' => 'panel')); ?>
	<div class="panel-body">
		<p class="text-muted"><?php echo __('Enter your e-mail, which you want to forgot password.'); ?></p>
		<hr class="panel-wide" />
		
		<div class="input-group input-group-lg">
			<span class="input-group-addon"><?php echo UI::icon('envelope'); ?></span>
			<?php echo Form::input('forgot[email]', NULL, array(
				'class' => 'form-control',
				'placeholder' => __('E-mail address')
			)); ?>
		</div>
	</div>
	<?php Observer::notify( 'admin_login_forgot_form' ); ?>
	<div class="panel-footer">	
		<?php echo Form::button('send', __('Send password'), array(
			'class' => 'btn btn-primary', 'data-icon-append' => 'paper-plane-o fa-lg'
		)); ?>			
	</div>
	<?php Form::close(); ?>
</div>