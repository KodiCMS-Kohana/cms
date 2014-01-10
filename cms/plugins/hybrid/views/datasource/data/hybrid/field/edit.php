<script>
	var DS_ID = '<?php echo $ds->ds_id; ?>';
</script>
<div class="widget">
<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal'
)); ?>
	<div class="widget-header">
		<h3><?php echo __( 'Edit field' ); ?></h3>
	</div>
	<div class="widget-content widget-no-border-radius" id="filed-type">
		<div class="control-group">
			<label class="control-label" for="name"><?php echo __('Field key'); ?></label>
			<div class="controls">
				<?php echo Form::hidden( 'name', Arr::get($post_data, 'name', $field->name)); ?>
				<?php echo Form::hidden( 'in_headline', Arr::get($post_data, 'in_headline', $field->in_headline)); ?>
				<span class="input-xlarge uneditable-input"><?php echo $field->name; ?></span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="header"><?php echo __('Field header'); ?></label>
			<div class="controls">
				<?php echo Form::input( 'header', Arr::get($post_data, 'header', $field->header), array(
					'class' => 'input-xlarge', 'id' => 'header'
				) ); ?>
			</div>
		</div>
	</div>

		<?php
		try
		{
			echo View::factory('datasource/data/hybrid/field/edit/' . $type, array(
				'field' => $field, 'post_data' => $post_data, 'sections' => $sections
			));
		}
		catch(Exception $e) {} ?>
	<div class="widget-content widget-no-border-radius">
		<div class="control-group">
			<label class="control-label" for="isreq"><?php echo __('Required'); ?></label>
			<div class="controls">
				<div class="checkbox">
					<?php echo Form::checkbox( 'isreq', 1, (Arr::get($post_data, 'isreq', $field->isreq) == 1), array(
						'id' => 'isreq'
					)); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="widget-footer form-actions">
		<?php echo UI::actions(NULL, Route::url('datasources', array(
			'controller' => 'section',
			'directory' => 'hybrid',
			'action' => 'edit',
			'id' => $ds->ds_id
		))); ?>
	</div>
<?php echo Form::close(); ?>
</div>