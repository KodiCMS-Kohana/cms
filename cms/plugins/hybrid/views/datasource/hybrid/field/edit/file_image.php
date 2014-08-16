<div class="form-group">
	<label class="control-label"><?php echo __( 'Max file size' ); ?></label>
	<div class="controls">
		<?php echo Form::input('max_size', $field->max_size, array('class' => 'input-small', 'id' => 'max_size')); ?> (<?php echo Text::bytes($field->max_size); ?>)
		<span class="flags">
			<span class="label" data-value="<?php echo NUM::bytes('100K'); ?>">100k</span>
			<span class="label" data-value="<?php echo NUM::bytes('1MiB'); ?>">1Mib</span>
			<span class="label" data-value="<?php echo NUM::bytes('5MiB'); ?>">5Mib</span>
			<span class="label" data-value="<?php echo NUM::bytes('10MiB'); ?>">10Mib</span>
		</span>
	</div>
</div>

<div class="widget-header">
	<h3><?php echo __('Image settings'); ?></h3>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label"><?php echo __( 'Image size' ); ?></label>
		<div class="controls">
			<?php echo Form::input('width', $field->width, array('class' => 'span1')); ?> x <?php echo Form::input('height', $field->height, array('class' => 'span1')); ?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label"><?php echo __( 'Image quality' ); ?></label>
		<div class="controls">
			<?php echo Form::input('quality', $field->quality, array('class' => 'span1')); ?>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label" for="crop"><?php echo __('Crop image'); ?></label>
		<div class="controls">
			<?php echo Form::checkbox('crop', 1, $field->crop == 1, array('id' => 'crop' )); ?>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label" for="master"><?php echo __('Resizing constraints'); ?></label>
		<div class="controls">
			<?php echo Form::select( 'master', array(
				Image::NONE => __('Ignoring aspect ratio'),
				Image::AUTO => __('Choose direction with the greatest reduction ratio'),
				Image::INVERSE => __('Choose direction with the minimum reduction ratio'),
				Image::HEIGHT => __('Recalculate the width based on the height proportions'),
				Image::WIDTH => __('Recalculate the height based on the width proportions'),
				Image::PRECISE => __('Resize to precise size')
			), $field->master, array('class' => 'input-block-level')); ?>
		</div>
	</div>
</div>