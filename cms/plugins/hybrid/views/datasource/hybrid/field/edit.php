<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal panel'
)); ?>
	<div class="panel-heading panel-toggler" data-icon="exclamation-circle">
		<span class="panel-title"><?php echo __('Field description'); ?></span>
	</div>
	<div class="panel-body panel-spoiler" id="filed-type">
		<div class="form-group form-group-lg">
			<label class="control-label col-md-3" for="header"><?php echo __('Field header'); ?></label>
			<div class="col-md-9">
				<?php echo Form::input('header', Arr::get($post_data, 'header', $field->header), array(
					'class' => 'form-control', 'id' => 'header'
				)); ?>
			</div>
		</div>
		<div class="form-group form-inline">
			<label class="control-label col-md-3" for="name"><?php echo __('Field key'); ?></label>
			<div class="col-md-9">
				<?php echo Form::hidden('name', Arr::get($post_data, 'name', $field->name)); ?>
				<?php echo Form::hidden('in_headline', Arr::get($post_data, 'in_headline', $field->in_headline)); ?>
				
				<?php echo Form::input(NULL, $field->name, array(
					'class' => 'form-control', 'disabled', 'uneditable'
				)); ?>
			</div>
		</div>
	</div>
	
	<div class="panel-heading">
		<span class="panel-title"><?php echo __( 'Field settings' ); ?></span>
	</div>
	<div class="panel-body ">
		<?php
		try
		{
			if( ! empty($post_data))
			{
				$field->set($post_data);
			}
			echo View::factory('datasource/hybrid/field/edit/' . $type, array(
				'field' => $field, 'sections' => $sections
			));
		}
		catch(Exception $e) {} 
		?>
		
		<hr class="panel-wide" />
		
		<?php if($field->is_required()): ?>
		<?php echo View::factory('datasource/hybrid/field/edit/system_isreq', array(
			'field' => $field, 'post_data' => $post_data
		)); ?>
		<?php endif; ?>

		<?php echo View::factory('datasource/hybrid/field/edit/system_hint', array(
			'field' => $field, 'post_data' => $post_data
		)); ?>

		<?php echo View::factory('datasource/hybrid/field/edit/system_position', array(
			'field' => $field, 'post_data' => $post_data
		)); ?>
	</div>
	<div class="panel-footer form-actions">
		<?php echo UI::actions(NULL, Route::get('datasources')->uri(array(
			'controller' => 'section',
			'directory' => 'datasources',
			'action' => 'edit',
			'id' => $ds->id()
		))); ?>
	</div>
<?php echo Form::close(); ?>