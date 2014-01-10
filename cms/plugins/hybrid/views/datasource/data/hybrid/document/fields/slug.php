<div class="control-group">
	<label class="control-label" for="<?php echo $field->name; ?>"><?php echo $field->header; ?></label>
	<div class="controls">
		<div class="row-fluid">
			<div class="input-append span10">
				<?php echo Form::input( $field->name, $value, array(
					'class' => 'input-block-level slug ' . (!empty($field->from_header) ? 'from-header' : ''), 'id' => $field->name,
					'maxlength' => 255, 'data-separator' => $field->separator
				) ); ?>

				<?php echo Form::button('copy_from_header', UI::icon('magnet'), array('class' => 'btn')); ?>
				
				<script>
					$(function() {
						$('button[name="copy_from_header"]').on('click', function() {
							$(this).prev().val($('input[name="header"]').val()).keyup()
							return false;
						});
					})
				</script>
			</div>
		</div>
	</div>
</div>