<div class="form-group">
	<label class="control-label col-md-3"><?php echo __('Default filter'); ?></label>
	<div class="col-md-4">
		<?php echo Form::select('filter', WYSIWYG::html_select(), $field->filter); ?>
	</div>
</div>

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('remove_empty_tags', 1, $field->remove_empty_tags == 1, array(
					'id' => 'remove_empty_tags'
				)); ?> <?php echo __('Remove empty tags'); ?>
			</label>
		</div>
		
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('filter_html', 1, $field->filter_html == 1, array(
					'id' => 'html_filter_html'
				)); ?> <?php echo __('Filter HTML tags'); ?>
			</label>
		</div>
	</div>
</div>

<div class="form-group" id="html_allowed_tags_container">
	<label class="control-label col-md-3" for="html_allowed_tags"><?php echo __('Allowed tags'); ?></label>
	<div class="col-md-9">
		<?php echo Form::textarea('allowed_tags', $field->allowed_tags, array('id' => 'html_allowed_tags', 'rows' => 2, 'class' => 'form-control')); ?>
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