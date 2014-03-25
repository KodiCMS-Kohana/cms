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

<div class="widget">
<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal'
)); ?>

	<?php echo Form::hidden('ds_id', $ds->id()); ?>
	
	<div class="widget-header">
		<h3><?php echo __( 'Add field' ); ?></h3>
	</div>

	<div class="widget-content" id="filed-type">
		<div class="control-group">
			<label class="control-label title" for="header"><?php echo __('Field header'); ?></label>
			<div class="controls">
				<?php echo Form::input( 'header', Arr::get($post_data, 'header'), array(
					'class' => 'slug-generator input-title input-block-level', 'id' => 'header', 'data-separator' => '_'
				) ); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="name"><?php echo __('Field key'); ?></label>
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on"><?php echo DataSource_Hybrid_Field::PREFFIX; ?></span>
						<?php echo Form::input( 'name', Arr::get($post_data, 'name'), array(
						'class' => 'input-xlarge slug', 'id' => 'name'
					) ); ?>
				</div>
				
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="select-field-type"><?php echo __('Field type'); ?></label>
			<div class="controls">
				<?php echo Form::select( 'type', DataSource_Hybrid_Field::types(), Arr::get($post_data, 'type'), array(
					'id' => 'select-field-type'
				)); ?>
			</div>
		</div>
	</div>
		
	<div class="widget-header">
		<h3><?php echo __( 'Field settings' ); ?></h3>
	</div>
	<div class="widget-content">
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
		</fieldset>
		<?php endforeach; ?>
		</div>
		<div class="control-group">
			<label class="control-label" for="position"><?php echo __('Field position'); ?></label>
			<div class="controls">
				<?php echo Form::input( 'position', Arr::get($post_data, 'position', 500), array(
					'id' => 'position',
					'class' => 'input-mini'
				)); ?>
			</div>
		</div>
	</div>
	<div class="widget-footer form-actions">
		<?php echo UI::button( __('Add field'), array(
			'icon' => UI::icon( 'plus'), 'class' => 'btn btn-large'
		)); ?>
	</div>
<?php echo Form::close(); ?>
</div>