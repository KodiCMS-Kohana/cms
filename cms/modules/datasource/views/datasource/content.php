<div class="page-mail">
	<div class="mail-nav">
		<?php echo $menu ?>
		<?php if(isset($toolbar)): ?>
		<?php echo $toolbar; ?>
		<?php endif; ?>
	</div>
	<div class="mail-container">
		<?php echo $content; ?>
	</div>
	<div class="clearfix"></div>
</div>