<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="next_url"><?php echo __('Allowed tags'); ?></label>
		<div class="col-md-9">
			<?php echo Form::textarea( 'allowed_tags', $widget->get('allowed_tags'), array(
				'class' => 'form-control', 'rows' => 2
			)); ?>
		</div>
	</div>
	
	<hr />
	
	<div class="form-group">
		<label class="control-label col-md-3" for="next_url"><?php echo __('Next page after success send (URL)'); ?></label>
		<div class="col-md-3">
			<?php echo Form::input( 'next_url', $widget->next_url, array(
				'class' => 'form-control'
			)); ?>
		</div>
	</div>
</div>

<div class="panel-heading">
	<span class="panel-title"><?php echo __('Message fields'); ?></span>
</div>

<script type="text/javascript">
	var add_field = function() {
		return $('#sample_field .field')
			.clone()
			.appendTo(fields_container);
	}

	var set_field = function(field_container, data) {
		$('.select2-container', field_container).remove()
		$("select", field_container).select2();
			
		for(key in data) {
			$('input[name="field[' + key +'][]"]', field_container).val(data[key]);
			$('select[name="field[' + key +'][]"]', field_container).val(data[key]).trigger("change");
			
			if(key == 'id') {
				$(field_container).find('.field-title').text(data[key]);
			}
		}
		
		$('input[name="field[id][]"]', field_container).on('keyup', function() {
			field_container.find('.field-title').text($(this).val());
		});
	}
	$(function() {
		var fields_container = $('#fields_container');
		$('#add_field').on('click', function() {
			var field = add_field();

			$('.select2-container', field).remove()
			$("select", field).select2();
			
			$('input[name="field[id][]"]', field).on('keyup', function() {
				field.find('.field-title').text($(this).val());
			});

			return false;
		});
		
		$('#fields_container').on('click', '.remove_field', function() {
			$(this).parents('.field').remove();
		});
	})
</script>

<div class="panel-body " id="fields">
	<div id="sample_field" class="hide">
		<div class="well well-sm field">
			<span class="panle-title field-title"></span>
			<table class="table table-noborder">
				<colgroup>
					<col width="200px" />
					<col width="150px" />
					<col />
					<col width="200px" />
					<col width="50px" />
				</colgroup>
				<tbody>
					<tr>
						<td></td>
						<td><?php echo __('Field source')?></td>
						<td><?php echo __('Field source key')?></td>
						<td nowrap><?php echo __('Field value type')?></td>
						<td rowspan="7" valign="top" class="text-right">
							<?php echo UI::button(NULL, array('icon' => UI::icon('trash-o'), 'class' => 'btn-danger remove_field')); ?>
						</td>
					</tr>
					<tr>
						<td class="text-right"><strong><?php echo __('Field'); ?></strong></td>
						<td>
							<?php echo Form::select('field[source][]', $widget->src_types()); ?>
						</td>
						<td>
							<?php echo Form::input('field[id][]', NULL, array(
								'class' => 'form-control'
							)); ?>
						</td>
						<td>
							<?php echo Form::select('field[type][]', $widget->value_types()); ?>
						</td>
					</tr>
					
					<tr>
						<td class="text-right"><strong><?php echo __('Field name'); ?></strong></td>
						<td colspan="3">
							<?php echo Form::input('field[name][]', NULL, array(
								'class' => 'form-control'
							)); ?>
						</td>
					</tr>

					<tr>
						<td></td>
						<td nowrap><?php echo __('Validation'); ?></td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td class="text-right"><strong><?php echo __('Validator'); ?></strong></td>
						<td colspan="3">
							<?php echo Form::input('field[validator][]', NULL, array(
								'class' => 'form-control'
							)); ?>
							<br />
							<span class="flags" data-append="true" data-target="">
								<span class="label" data-value="not_empty"><?php echo __('Not empty'); ?></span>
								<span class="label" data-value="url"><?php echo __('URL'); ?></span>
								<span class="label" data-value="phone"><?php echo __('Phone number'); ?></span>
								<span class="label" data-value="email"><?php echo __('Email'); ?></span>
								<span class="label" data-value="email_domain"><?php echo __('Email domain'); ?></span>
								<span class="label" data-value="ip"><?php echo __('IP'); ?></span>
								<span class="label" data-value="credit_card"><?php echo __('Credit card'); ?></span>
								<span class="label" data-value="date"><?php echo __('Date'); ?></span>
								<span class="label" data-value="alpha"><?php echo __('Alpha'); ?></span>
								<span class="label" data-value="alpha_dash"><?php echo __('Alpha and hyphens'); ?></span>
								<span class="label" data-value="alpha_numeric"><?php echo __('Alpha and numbers'); ?></span>
								<span class="label" data-value="digit"><?php echo __('Integer digit'); ?></span>
								<span class="label" data-value="decimal"><?php echo __('Decimal'); ?></span>
								<span class="label" data-value="numeric"><?php echo __('Numeric'); ?></span>
								<span class="label" data-value="color"><?php echo __('Color'); ?></span>
							</span>
						</td>
					</tr>
					<tr><td colspan="4"><hr class="panel-wide no-margin-vr" /></td></tr>
					<tr>
						<td></td>
						<td nowrap><?php echo __('Field error message'); ?></td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td class="text-right"><strong><?php echo __('Field error'); ?></strong></td>
						<td colspan="3">
							<?php echo Form::input('field[error][]', NULL, array(
								'class' => 'form-control'
							)); ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<?php 
	if(!empty($widget->fields))
	{
		echo '<script> $(function(){';
		foreach($widget->fields as $field)
			echo 'set_field(add_field(), ' . json_encode( $field ) . '); ';
		echo '});</script>';
	}
	?>
	
	<div id="fields_container"></div>
	<?php echo UI::button(__('Add field'), array('icon' => UI::icon('plus'), 'id' => 'add_field', 'class' => 'btn-default btn-lg')); ?>
</div>