<div class="control-group">
	<label class="control-label" for="allow_html"><?php echo __('Allow HTML tags'); ?></label>
	<div class="controls">
		<?php echo Form::checkbox('allow_html', 1, $field->allow_html == 1, array('id' => 'allow_html' )); ?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="text_filter_html"><?php echo __('Filter HTML tags'); ?></label>
	<div class="controls">
		<?php echo Form::checkbox('filter_html', 1, $field->filter_html == 1, array('id' => 'text_filter_html' )); ?>
	</div>
</div>

<div class="control-group" id="text_allowed_tags_container">
	<label class="control-label" for="text_allowed_tags"><?php echo __('Allowed tags'); ?></label>
	<div class="controls">
		<?php echo Form::textarea('allowed_tags', $field->allowed_tags, array('id' => 'text_allowed_tags', 'rows' => 2 )); ?>
	</div>
</div>

<hr />

<div class="control-group">
	<label class="control-label" for="rows"><?php echo __('Rows'); ?></label>
	<div class="controls">
		<?php echo Form::input( 'rows', $field->rows, array(
			'class' => 'input-mini', 'id' => 'rows'
		) ); ?>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		$(document).on('change', '#text_filter_html', function() {
			if($(this).is(':checked')) {
				$('#text_allowed_tags_container').show();
			} else {
				$('#text_allowed_tags_container').hide();
			}
		}).change();
	})
</script>