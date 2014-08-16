<div class="widget">
	<?php echo Form::open(Request::current()->uri(), array(
		'class' => Bootstrap_Form::HORIZONTAL
	)); ?>
	<div class="panel-body">
		<?php if( ! empty($patches)): ?>
		<div class="form-group">
			<div class="controls">
				<?php if ( ! is_dir(PATCHES_FOLDER)): ?>
				<div class="alert alert-warning">
					<?php echo UI::icon('lightbulb-o'); ?>
					<?php echo __('You need to create a folder :folder and set access rights to :chmod', array(
						':folder' => PATCHES_FOLDER,
						':chmod' => '0777'
					)); ?>
				</div>
				<?php endif; ?>
				
				<h3><?php echo __('Select patch to apply'); ?></h3>
				
				<?php echo Form::select( 'patch', $patches, NULL, array(
					'class' => 'input-xxlarge'
				) ); ?>
			</div>
		</div>
		<?php else: ?>
		<h2><?php echo __('No available patches'); ?></h2>
		<?php endif; ?>
	</div>
	
	<?php if( ! empty($patches)): ?>
	<div class="panel-footer form-actions">
		<div class="form-group">
			<div class="controls">
				<?php echo Form::button('apply', __('Apply'), array('class' => 'btn btn-confirm btn-danger')); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php echo Form::close(); ?>
</div>