<div class="widget">
	<div class="widget-header">
		<h3><?php echo __('Database update SQL script'); ?></h3>
		
		<?php if(ACL::check('update.database_apply')): ?>
		<?php echo Form::button('apply', __('Apply'), array('class' => 'btn btn-danger btn-api', 'data-url' => 'update.database')); ?>
		<?php endif; ?>
	</div>

	<div class="panel-body widget-nopad">
		<?php if(!empty($actions)): ?>
		<textarea id="highlight_content" data-readonly="on" data-mode="mysql">
SET FOREIGN_KEY_CHECKS = 0;

<?php echo HTML::chars($actions); ?>

SET FOREIGN_KEY_CHECKS = 1;</textarea>
		<?php else: ?>
		<h2 style="padding: 20px 40px;"><?php echo __('There are no changes to the database structure'); ?></h2>
		<?php endif; ?>
	</div>
</div>