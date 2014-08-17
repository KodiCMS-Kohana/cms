<?php echo Form::open(NULL, array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data')); ?>

	<?php echo Form::hidden('ds_id', $datasource->id()); ?>
	<?php echo Form::hidden('id', $document->id); ?>

	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Header'); ?></label>
		<div class="controls">
			<?php echo Form::input('header', $document->header, array('class' => 'input-block-level')); ?>
		</div>

		<div class="controls">
			<?php echo View::factory('datasource/hybrid/document/fields/published', array(
				'doc' => $document
			)); ?>
		</div>	
	</div>

	<div class="well well-small">
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Meta title'); ?></label>
			<div class="controls">
				<?php echo Form::input('meta_title', $document->meta_title, array(
					'class' => 'input-block-level'
				)); ?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Meta keywords'); ?></label>
			<div class="controls">
				<?php echo Form::input('meta_keywords', $document->meta_keywords, array(
					'class' => 'input-block-level'
				)); ?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Meta description'); ?></label>
			<div class="controls">
				<?php echo Form::textarea('meta_description', $document->meta_description, array(
					'class' => 'input-block-level'
				)); ?>
			</div>
		</div>
	</div>
	<?php foreach ($fields as $key => $field): ?>
	<?php echo $field->backend_template($document); ?>
	<?php endforeach; ?>

	<div class="form-actions">
		<button name="commit" class="btn btn-info btn-lg"><?php echo __('Save'); ?></button>
	</div>
<?php echo Form::close(); ?>