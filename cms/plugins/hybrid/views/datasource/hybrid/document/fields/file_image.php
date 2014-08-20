<div class="form-group">
	<label class="control-label col-md-3" for="<?php echo $field->name; ?>">
		<?php echo $field->header; ?> 
	</label>
	<div class="col-md-9" id="file-<?php echo $field->name; ?>">
			<?php if( ! empty($value)): ?>
			<div class="panel">
				<div class="panel-heading panel-toggler" data-icon="chevron-down">
					<span class="panel-title"><?php echo __('Upload new file'); ?></span>
				</div>
				<div class="panel-body panel-spoiler">
					<?php echo HTML::anchor(PUBLIC_URL . $value, UI::icon('file' ) . ' ' . __('View file'), array(
						'target' => 'blank', 
						'class' => 'btn popup'
					)); ?>
					&nbsp;&nbsp;&nbsp;
					<div class="checkbox checkbox-inline">
						<label>
							<?php echo Form::checkbox( $field->name . '_remove', 1, FALSE, array('class' => 'remove-file-checkbox')); ?> <?php echo __('Remove file'); ?>
						</label>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<div class="upload-new-cont <?php if( ! empty($value)): ?>spoiler<?php endif; ?> spoiler-file-<?php echo $field->name; ?>">
				<div class="form-group">
					<div class="col-md-5">
						<?php echo Form::file($field->name, array(
							'id' => $field->name, 'class' => 'form-control upload-input'
						)); ?>
					</div>
					<div class="col-md-7">
						<div class="input-group">
							<?php echo Form::input($field->name . '_url', NULL, array(
								'id' => $field->name . '_url', 'placeholder' => __('Or paste URL to file'),
								'class' => 'form-control', 'data-filemanager' => 'true'
							)); ?>

							<div class="input-group-btn"></div>
						</div>
					</div>
				</div>
				<p class="help-block">
					<?php echo __('Max file size: :size', array(
					':size' => Text::bytes($field->max_size)
					)); ?>
				</p>
			</div>
	</div>
</div>

<script>
	$(function() {
		var cont = $('#file-<?php echo $field->name; ?>');
		
		$('.remove-file-checkbox', cont).on('change', function() {
			if($(this).is(':checked')) {
				$('#uploaded-<?php echo $field->name; ?>').hide();
				$('.upload-new-cont input', cont).attr('disabled', 'disabled');
				$('.panel-toggler,hr', cont).hide();
			} else {
				$('.panel-toggler,hr', cont).show();
				$('#uploaded-<?php echo $field->name; ?>').show();
				$('.upload-new-cont input', cont).removeAttr('disabled');
			}
		})
	})
</script>