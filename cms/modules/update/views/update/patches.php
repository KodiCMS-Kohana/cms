<?php echo Form::open(Request::current()->uri(), array(
	'class' => array(Form::HORIZONTAL, 'panel')
)); ?>
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Patches'); ?></span>
	</div>

	<?php if (!is_dir(PATCHES_FOLDER)): ?>
	<div class="alert alert-danger alert-dark">
		<?php echo UI::icon('lightbulb-o fa-lg'); ?>
		<?php echo __('You need to create a folder :folder and set access rights to :chmod', array(
			':folder' => PATCHES_FOLDER,
			':chmod' => '0777'
		)); ?>
	</div>
	<?php endif; ?>

	<div class="panel-body">
		<?php if (!empty($patches)): ?>
		<h4 class="no-margin-t"><?php echo __('Select patch to apply'); ?></h4>

		<?php echo Form::select( 'patch', $patches, NULL); ?>
		<?php else: ?>
		<h2><?php echo __('No available patches'); ?></h2>
		<?php endif; ?>
	</div>

	<?php if( ! empty($patches)): ?>
	<div class="panel-footer form-actions">
		<?php echo Form::button('apply', __('Apply'), array('class' => 'btn btn-confirm btn-danger')); ?>
	</div>
	<?php endif; ?>
<?php echo Form::close(); ?>