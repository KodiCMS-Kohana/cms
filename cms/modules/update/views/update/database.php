<div class="panel">
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Database update SQL script'); ?></span>
		
		<?php if(ACL::check('update.database_apply')): ?>
		<div class="panel-heading-controls">
			<?php echo Form::button('apply', __('Apply'), array('class' => 'btn btn-danger', 'data-api-url' => 'update.database')); ?>
		</div>
		<?php endif; ?>
	</div>
	
	<?php if(!empty($actions)): ?>
	<textarea id="highlight_content" data-readonly="on" data-mode="mysql">
SET FOREIGN_KEY_CHECKS = 0;

<?php echo HTML::chars($actions); ?>

SET FOREIGN_KEY_CHECKS = 1;</textarea>
	<?php else: ?>
	<div class="panel-body">
		<h2 class="no-margin-vr"><?php echo __('There are no changes to the database structure'); ?></h2>
	</div>
	<?php endif; ?>
</div>