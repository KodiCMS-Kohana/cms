<div class="control-group">
	<label class="control-label" for="<?php echo $field->name; ?>"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="controls">
		<div class="file-upload btn">
			<span><?php echo __('Select file to upload'); ?></span>
			<?php echo Form::file( $field->name . '[]', array(
				'id' => $field->name, 
				'multiple', 
				'class' => 'upload-input',
				'data-target' => $field->name . '_preview',
				'data-size' => $field->max_size
			) ); ?>
		</div>
		<div id="<?php echo $field->name; ?>_preview"></div>
		<span class="help-block">
			<?php echo __('Max file size: :size', array(
			':size' => Text::bytes($field->max_size)
			)); ?>
		</span>
			
		<?php $files = $field->load($value); ?>
		<?php if(!empty($files)): ?>
		<br />
		<div class="well">
		<ul class="thumbnails">
		<?php foreach ($files as $id => $file): ?>
			<li>
				<?php $image = HTML::image(Image::cache($file, 100, 100, Image::HEIGHT));
				echo HTML::anchor(PUBLIC_URL . $file, $image, array('class' => 'popup thumbnail', 'data-title' => 'false', 'rel' => $field->name)); ?>
				<label class="text-center"><?php echo Form::checkbox($field->name . '_remove[]', $id) . '<br />' . __('Remove'); ?></label>
			</li>
		<?php endforeach; ?>
		</ul>
			</div>
		<?php endif; ?>
	</div>
</div>