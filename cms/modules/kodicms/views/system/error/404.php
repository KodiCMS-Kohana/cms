<div id="error-container" class="hero-unit">
	<h1><?php echo __('Oooops'); ?></h1>

	<div class="error-code">
		<h3><?php echo $code; ?> <?php echo $error_type; ?></h3>
	</div>
	<p>
		<?php echo $message; ?>
	</p>

	<div class="error-actions">
		<?php echo UI::button(__('Back'), array(
			'href' => URL::site('', TRUE), 'icon' => UI::icon( 'chevron-left' ),
			'class' => 'btn btn-large'
		)); ?>
	</div>
</div>