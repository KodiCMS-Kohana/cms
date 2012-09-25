<div id="error-container" class="hero-unit">
	<h1>Упс!</h1>

	<div class="error-code">
		<h3><?php echo $code; ?> <?php echo $error_type; ?></h3>
	</div>
	
	<p><?php echo __('Sorry, an error has occured, Requested page not found!'); ?></p>
	
	<span class="error-details badge badge-important">
		<?php echo $message; ?>
	</span>

	<div class="error-actions">
		<?php echo UI::button(__('Back to Homepage'), array(
			'href' => URL::base(TRUE), 'icon' => UI::icon( 'chevron-left' ),
			'class' => 'btn btn-large'
		)); ?>
	</div>
</div>