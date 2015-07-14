<div class="form-group form-inline">
	<label class="control-label col-md-3"><?php echo __('Max file size'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('max_size', $field->max_size, array(
			'class' => 'form-control', 'id' => 'max_size', 'size' => 10
		)); ?>&nbsp;&nbsp;&nbsp;(<?php echo Text::bytes($field->max_size); ?>)
		<span class="flags">
			<span class="label" data-value="<?php echo NUM::bytes('100K'); ?>">100k</span>
			<span class="label" data-value="<?php echo NUM::bytes('1MiB'); ?>">1Mib</span>
			<span class="label" data-value="<?php echo NUM::bytes('5MiB'); ?>">5Mib</span>
			<span class="label" data-value="<?php echo NUM::bytes('10MiB'); ?>">10Mib</span>
		</span>
	</div>
</div>

<div class="panel-heading">
	<span class="panel-title"><?php echo __('Image settings'); ?></span>
</div>
<div class="panel-body well">
	<div class="form-group form-inline">
		<label class="control-label col-md-3"><?php echo __('Image size'); ?></label>
		<div class="col-md-9">
			<div class="input-group">
				<?php echo Form::input('width', $field->width, array('class' => 'form-control', 'size' => 6)); ?>
				<div class="input-group-addon">x</div>
				<?php echo Form::input('height', $field->height, array('class' => 'form-control', 'size' => 6)); ?>
			</div>
		</div>
	</div>
	
	<div class="form-group form-inline">
		<label class="control-label col-md-3"><?php echo __('Image quality'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('quality', $field->quality, array('class' => 'form-control', 'size' => 3, 'maxlength' => 3)); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label>
					<?php echo Form::checkbox('crop', 1, $field->crop == 1, array(
						'id' => 'crop'
					)); ?> <?php echo __('Crop image'); ?>
				</label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="master"><?php echo __('Resizing constraints'); ?></label>
		<div class="col-md-9">
			<?php echo Form::select( 'master', array(
				Image::NONE => __('Ignoring aspect ratio'),
				Image::AUTO => __('Choose direction with the greatest reduction ratio'),
				Image::INVERSE => __('Choose direction with the minimum reduction ratio'),
				Image::HEIGHT => __('Recalculate the width based on the height proportions'),
				Image::WIDTH => __('Recalculate the height based on the width proportions'),
				Image::PRECISE => __('Resize to precise size')
			), $field->master); ?>
		</div>
	</div>
</div>

<div class="panel-heading">
	<span class="panel-title"><?php echo __('Watermark'); ?></span>
</div>
<div class="panel-body well">
	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label>
					<?php echo Form::checkbox('watermark', 1, $field->watermark == 1, array(
						'id' => 'watermark'
					)); ?> <?php echo __('Add a watermark to an image'); ?>
				</label>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Watermark image path'); ?></label>
		<div class="col-md-9">
			<div class="input-group">
				<div class="input-group-addon"><?php echo DOCROOT; ?></div>
				<?php echo Form::input('watermark_path', $field->watermark_path, array('class' => 'form-control')); ?>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label>
					<?php echo Form::checkbox('watermark_center', 1, $field->watermark_center == 1, array(
						'id' => 'watermark_center'
					)); ?> <?php echo __('Watermark center of the axis'); ?>
				</label>
			</div>
		</div>
	</div>
	
	<div class="form-group form-inline">
		<label class="control-label col-md-3"><?php echo __('Watermark offset'); ?></label>
		<div class="col-md-9">
			<div class="input-group">
				<div class="input-group-addon ">X</div>
				<?php echo Form::input('watermark_offset_x', $field->watermark_offset_x, array('class' => 'form-control', 'size' => 6)); ?>
				<div class="input-group-addon">px</div>
			</div>
			
			<div class="input-group">
				<div class="input-group-addon">Y</div>
				<?php echo Form::input('watermark_offset_y', $field->watermark_offset_y, array('class' => 'form-control', 'size' => 6)); ?>
				<div class="input-group-addon">px</div>
			</div>
		</div>
	</div>
	
	<div class="form-group form-inline">
		<label class="control-label col-md-3"><?php echo __('Watermark opacity'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('watermark_opacity', $field->watermark_opacity, array('class' => 'form-control', 'size' => 3, 'maxlength' => 3)); ?>
		</div>
	</div>
</div>