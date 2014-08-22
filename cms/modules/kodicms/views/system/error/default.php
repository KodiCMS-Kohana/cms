<div class="frontend-header">
	<a href="/" class="logo">
		<?php echo HTML::image(ADMIN_RESOURCES . 'images/logo-color.png'); ?>
	</a>
</div>

<div class="error-container">
	<div class="error-code"><?php echo $code; ?></div>
	<div class="error-text"><span class="oops"><?php echo $error_type; ?></span></div>
	<div class="error-text">
		<span class="hr"></span>
		<p><?php echo $message; ?></p>
	</div>
</div>