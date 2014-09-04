<script type="text/javascript">
	$(function() {
		var $container = $('#field-options');

		$container.on('click', '.add-row', function(e) {
			clone_row($container);
			e.preventDefault();
		});
		
		$container.on('click', '.remove-row', function(e) {
			$(this).closest('.row-helper').remove();
			e.preventDefault();
		});
	});
	
	function clone_row($container) {
		return $('.row-helper.hidden', $container)
			.clone()
			.removeClass('hidden')
			.appendTo($('.rows-container', $container))
			.find(':input')
			.removeAttr('disabled')
			.end();
	}
</script>

<div class="form-group" id="field-options">
	<label class="control-label col-md-3"><?php echo __('Field options'); ?></label>
	<div class="col-xs-9">
		<div class="row-helper hidden padding-xs-vr">
			<div class="input-group">
				<?php echo Form::input('new_options[]', NULL, array(
					'disabled', 'class' => 'row-value form-control', 
					'placeholder' => __('Value')
				)); ?>
				<div class="input-group-btn">
					<?php echo Form::button('trash-row', UI::icon('trash-o'), array(
						'class' => 'btn btn-warning remove-row'
					)); ?>
				</div>
			</div>
		</div>

		<div class="rows-container">
			<?php foreach ($field->load_from_db() as $id => $name): ?>
			<div class="row-helper padding-xs-vr">
				<div class="input-group">
					<?php echo Form::input('options['.$id.']', $name, array(
						'class' => 'row-value form-control', 
						'placeholder' => __('Value')
					)); ?>
					<div class="input-group-btn">
						<?php echo Form::button('trash-row', UI::icon('trash-o'), array(
							'class' => 'btn btn-warning remove-row'
						)); ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		
		<?php echo Form::button('add-row', UI::icon('plus'), array(
			'class' => 'add-row btn btn-primary', 'data-hotkeys' => 'ctrl+a'
		)); ?>
	</div>
</div>

<hr />

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('custom_option', 1, $field->custom_option == 1, array(
					'id' => 'custom_option'
				)); ?> <?php echo __('Can use custom value'); ?>
			</label>
		</div>

		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('empty_value', 1, $field->empty_value == 1, array(
					'id' => 'empty_value'
				)); ?> <?php echo __('Can select empty value'); ?>
			</label>
		</div>
	</div>
</div>