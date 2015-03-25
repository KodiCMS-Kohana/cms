<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('allow_html', 1, $field->allow_html == 1, array(
					'id' => 'allow_html'
				)); ?> <?php echo __('Allow HTML tags'); ?>
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('filter_html', 1, $field->filter_html == 1, array(
					'id' => 'text_filter_html'
				)); ?> <?php echo __('Filter HTML tags'); ?>
			</label>
		</div>
	</div>
</div>

<div class="form-group" id="text_allowed_tags_container">
	<label class="control-label col-md-3" for="text_allowed_tags"><?php echo __('Allowed tags'); ?></label>
	<div class="col-md-9">
		<?php echo Form::textarea('allowed_tags', $field->allowed_tags, array('id' => 'text_allowed_tags', 'rows' => 2, 'class' => 'form-control' )); ?>
	</div>
</div>

<hr />

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="rows"><?php echo __('Rows'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('rows', $field->rows, array(
			'class' => 'form-control', 'id' => 'rows', 'size' => 3, 'maxlength' => 3
		)); ?>
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