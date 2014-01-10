<div class="widget-content widget-no-border-radius">
	<div class="control-group">
		<label class="control-label"><?php echo __( 'Default filter' ); ?></label>
		<div class="controls">
			<select name="filter">
				<option value="">&ndash; <?php echo __( 'none' ); ?> &ndash;</option>
				<?php foreach (WYSIWYG::findAll() as $filter ): ?> 
				<option value="<?php echo $filter; ?>" <?php if($field->filter == $filter): ?>selected="selected"<?php endif; ?>><?php echo Inflector::humanize( $filter ); ?></option>
				<?php endforeach; ?> 
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="filter_html"><?php echo __('Filter HTML tags'); ?></label>
		<div class="controls">
			<?php echo Form::checkbox('filter_html', 1, $field->filter_html == 1, array('id' => 'filter_html' )); ?>
		</div>
	</div>
	<?php
	echo Bootstrap_Form_Element_Control_Group::factory(array(
			'element' => Bootstrap_Form_Element_Textarea::factory(array(
				'name' => 'allowed_tags', 'body' => $field->allowed_tags
			))
			->label(__('Allowed tags'))
		))->attributes('id', 'allowed_tags');
	?>
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