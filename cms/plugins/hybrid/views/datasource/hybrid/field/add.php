<script type="text/javascript">
	$(function() {
		$('#select-field-type').change(function() {
			var id = $(this).val();
			var fieldset = $('#field-options fieldset');

			fieldset
				.attr('disabled', 'disabled')
				.hide()
				.filter('fieldset#f-' + id)
				.show()
				.removeAttr('disabled')
				.end();

			$('select', fieldset).removeAttr('disabled')

		}).change();
	});
		
	var DS_ID = '<?php echo $ds->id(); ?>';
</script>

<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal panel'
)); ?>
	<?php echo Form::hidden('ds_id', $ds->id()); ?>
	
	<div class="panel-heading" data-icon="exclamation-circle">
		<span class="panel-title"><?php echo __('Field description'); ?></span>
	</div>

	<div class="panel-body" id="filed-type">
		<div class="form-group form-group-lg">
			<label class="control-label col-md-3" for="header"><?php echo __('Field header'); ?></label>
			<div class="col-md-9">
				<?php echo Form::input( 'header', Arr::get($post_data, 'header'), array(
					'class' => 'slug-generator form-control', 'id' => 'header', 'data-separator' => '_'
				) ); ?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3" for="name"><?php echo __('Field key'); ?></label>
			<div class="col-md-3">
				<div class="input-group">
					<span class="input-group-addon"><?php echo DataSource_Hybrid_Field::PREFFIX; ?></span>
					
					<?php echo Form::input( 'name', Arr::get($post_data, 'name'), array(
						'class' => 'form-control slug', 'id' => 'name'
					)); ?>
				</div>
				
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3" for="select-field-type"><?php echo __('Field type'); ?></label>
			<div class="col-md-3">
				<?php echo Form::select( 'type', DataSource_Hybrid_Field::types(), Arr::get($post_data, 'type'), array(
					'id' => 'select-field-type'
				)); ?>
			</div>
		</div>
	</div>
		
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Field settings'); ?></span>
	</div>
	<div class="panel-body">
		<div id="field-options">
		<?php foreach (DataSource_Hybrid_Field::get_empty_fields() as $type => $field): ?>
		<fieldset id="f-<?php echo $type; ?>" disabled="disabled">
		
		<?php
			try {
				$field->set($post_data);

				echo View::factory('datasource/hybrid/field/edit/' . $type, array(
					'field' => $field, 'sections' => $sections
				));
			} catch (Exception $exc) {}
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
		</fieldset>
		<?php endforeach; ?>
		</div>
	</div>
	<div class="panel-footer form-actions">
		<?php echo UI::button(__('Create field'), array(
			'icon' => UI::icon('plus'), 'class' => 'btn-lg btn-primary'
		)); ?>
		
		<?php echo UI::button(__('Save and create another'), array(
			'icon' => UI::icon('plus'), 'class' => 'btn-sm btn-default',
			'name' => 'save_and_create'
		)); ?>
	</div>
<?php echo Form::close(); ?>