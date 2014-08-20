<div class="form-group">
	<label class="control-label col-md-3" for="<?php echo $field->name; ?>">
		<?php echo $field->header; ?> 
	</label>
	<div class="col-md-9" id="file-<?php echo $field->name; ?>">
		<div class="row-fluid">
			<?php if( ! empty($value)): ?>
				<div class="well well-small">
					<div class="spoiler-file-<?php echo $field->name; ?>">
						<?php echo HTML::anchor(PUBLIC_URL . $value, UI::icon('file' ) . ' ' . __('View file'), array(
							'target' => 'blank', 
							'class' => 'btn popup'
						)); ?>
						&nbsp;&nbsp;&nbsp;
						<label class="checkbox inline">
						<?php echo Form::checkbox( $field->name . '_remove', 1, FALSE, array('class' => 'remove-file-checkbox')); ?> <?php echo __('Remove file'); ?>
						</label>
						<hr />
					</div>
					<div class="panel-toggler" data-target-spoiler=".spoiler-file-<?php echo $field->name; ?>">
						<?php echo UI::icon('chevron-down panel-toggler-icon'); ?> <?php echo __('Upload new file'); ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="upload-new-cont <?php if( ! empty($value)): ?>spoiler<?php endif; ?> spoiler-file-<?php echo $field->name; ?>">
				<div class="col-md-4">
					<div class="file-upload btn">
						<span><?php echo __('Select file to upload'); ?></span>
						<?php echo Form::file( $field->name, array(
							'id' => $field->name, 'class' => 'upload-input'
						) ); ?>
					</div>
					
					<br />
					<img src="" id="image_preview" />
					
					<span class="help-block">
						<?php echo __('Max file size: :size', array(
						':size' => Text::bytes($field->max_size)
						)); ?>
					</span>
				</div>
				
				
				
				<div class="col-md-7 input-append">
					<?php echo Form::input( $field->name . '_url', NULL, array(
						'id' => $field->name . '_url', 'placeholder' => __('Or paste URL to file'),
						'class' => 'form-control', 'data-filemanager' => 'true'
					) ); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(function() {
		var cont = $('#file-<?php echo $field->name; ?>');
		
		$('#<?php echo $field->name; ?>').on('change', function() {
			readImage(this, $('#image_preview'));
		});
		
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