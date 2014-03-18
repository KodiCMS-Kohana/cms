<div class="widget-content ">
	<div class="control-group">
		<label class="control-label" for="allow_html"><?php echo __('Allow HTML tags'); ?></label>
		<div class="controls">
			<?php echo Form::checkbox('allow_html', 1, $field->allow_html == 1, array('id' => 'allow_html' )); ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="filter_html"><?php echo __('Filter HTML tags'); ?></label>
		<div class="controls">
			<?php echo Form::checkbox('filter_html', 1, $field->filter_html == 1, array('id' => 'filter_html' )); ?>
		</div>
	</div>
	<?php echo Bootstrap_Form_Element_Control_Group::factory(array(
		'element' => Bootstrap_Form_Element_Textarea::factory(array(
			'name' => 'allowed_tags', 'body' => $field->allowed_tags
		))
		->label(__('Allowed tags'))
	))->attributes('id', 'allowed_tags'); ?>
	
	<hr />
	
	<div class="control-group">
		<label class="control-label" for="rows"><?php echo __('Rows'); ?></label>
		<div class="controls">
			<?php echo Form::input( 'rows', Arr::get($post_data, 'rows', $field->rows), array(
				'class' => 'input-mini', 'id' => 'rows'
			) ); ?>
		</div>
	</div>
</div>

<script>
	$(function() {
		$('#filter_html').on('change', function() {
			if($(this).is(':checked')) {
				$('#allowed_tags').show();
			} else {
				$('#allowed_tags').hide();
			}
		}).change();
	})
</script>