<script type="text/javascript">
	var EMAIL_TYPE_ID = <?php echo (int) $type->id; ?>;
	var EMAIL_TYPE_DATA = <?php echo json_encode($type->data()); ?>
	
	$(function() {
		$('#add-field').on('click', function() {
			$('#type-fields .field-row.hidden').clone().removeClass('hidden').prependTo($('#type-fields .controls'));
			return false;
		});
		
		$('#type-fields').on('click', '.remove-field', function() {
			$(this).parent().remove();
			return false;
		});

		for(key in EMAIL_TYPE_DATA) {
			var row = $('#type-fields .field-row.hidden')
					.clone()
					.removeClass('hidden')
					.prependTo($('#type-fields .controls'));

			row.find('.field_key_input').removeAttr('disabled').val(key);
			row.find('.field_desription_input').removeAttr('disabled').val(EMAIL_TYPE_DATA[key]);
		}
	});
</script>

<?php echo Form::open(Route::url('email_controllers', array('controller' => 'types', 'action' => $action, 'id' => $type->id)), array(
	'class' => Bootstrap_Form::HORIZONTAL
)); ?>

<?php echo Form::hidden('token', Security::token()); ?>
<div class="widget">
	<div class="widget-header">
		<h3><?php echo __('General information'); ?></h3>
	</div>
	<div class="widget-content">
		<div class="control-group">
			<label class="control-label title" for="name"><?php echo __( 'Email type name' ); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'name', $type->name, array(
					'class' => 'input-title input-block-level', 'id' => 'subject'
				) );
				?>
			</div>
		</div>
		
		
		<div class="control-group">
			<label class="control-label" for="code"><?php echo __( 'Email type code' ); ?></label>
			<div class="controls">
				<?php if($action == 'add'): ?>
				<?php echo Form::input( 'code', $type->code, array(
					'class' => 'slug', 'id' => 'code', 'data-separator' => '_'
				) ); ?>
				<?php else: ?>
				<span class="input-xlarge uneditable-input"><?php echo $type->code; ?></span>
				<?php endif; ?>
			</div>
		</div>
		
	</div>
	
	<div class="widget-header">
		<h3><?php echo __('Email type fileds'); ?></h3>
	</div>
	<div class="widget-content" id="type-fields">
		<div class="control-group">
			<div class="controls">
				<div class="field-row hidden">
					<input type="text" name="data[key][]" disabled="disabled" class="input-small slug field_key_input" data-separator="_" placeholder="Field key">
					<input type="text" name="data[name][]" disabled="disabled" class="input-xxlarge field_desription_input" placeholder="Desription">
					<button class="btn btn-mini remove-field"><?php echo UI::icon('trash'); ?></button>
					<br /><br />
				</div>
				<button id="add-field" class="btn"><?php echo UI::icon('plus'); ?></button>
			</div>
		</div>
		  
	</div>
	<div class="form-actions widget-footer">
		<?php echo UI::actions($page_name); ?>
	</div>
</div>
<?php Form::close(); ?>