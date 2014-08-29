<p class="muted"><?php echo __('Page :page is protected, you need input password to access this page', array(':page' => $current_page->title)); ?></p>
<br />
<div class="well">
	<h4><?php echo __('Page password'); ?></h4>
	<?php echo Form::open($page->url());?>
	<div class="input-append">
	  <?php echo Form::password('password');?>
		<?php echo Form::button('submit', 'submit', array('class' => 'btn btn-default'));?>
	</div>
	<?php echo Form::close(); ?>
</div>