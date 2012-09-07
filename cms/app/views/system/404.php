<div id="error-container" class="hero-unit">
	<h1>Oops!</h1>

	<div class="error-code">
		<h3><?php echo $code; ?> <?php echo $error_type; ?></h3>
	</div>
	
	<p><?php echo __('Sorry, an error has occured, Requested page not found!'); ?></p>
	
	<span class="error-details badge badge-important">
		<?php echo $message; ?>
	</span>

	<div class="error-actions">
		<hr />
		<a href="/" class="btn btn-large btn-primary">
			<i class="icon-chevron-left"></i>
			<?php echo __('Back to Homepage'); ?>				
		</a>
	</div>
</div>