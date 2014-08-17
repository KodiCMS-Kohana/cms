<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="field_id"><?php echo __('Hybrid field'); ?></label>
		<div class="col-md-9">
			<?php echo Form::select( 'field_id', $widget->get_doc_fields(), $widget->field_id, array(
				'id' => 'field_id'
			)); ?>
		</div>
	</div>
</div>