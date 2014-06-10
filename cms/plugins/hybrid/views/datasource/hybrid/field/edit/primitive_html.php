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
	<label class="control-label" for="html_filter_html"><?php echo __('Filter HTML tags'); ?></label>
	<div class="controls">
		<?php echo Form::checkbox('filter_html', 1, $field->filter_html == 1, array('id' => 'html_filter_html' )); ?>
	</div>
</div>

<div class="control-group" id="html_allowed_tags_container">
	<label class="control-label" for="html_allowed_tags"><?php echo __('Allowed tags'); ?></label>
	<div class="controls">
		<?php echo Form::textarea('allowed_tags', $field->allowed_tags, array('id' => 'html_allowed_tags', 'rows' => 2 )); ?>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		$(document).on('change', '#html_filter_html', function() {
			if($(this).is(':checked')) {
				$('#html_allowed_tags_container').show();
			} else {
				$('#html_allowed_tags_container').hide();
			}
		}).change();
	})
</script>