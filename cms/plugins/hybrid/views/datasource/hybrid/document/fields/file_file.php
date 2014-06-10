<div class="control-group">
	<label class="control-label" for="<?php echo $field->name; ?>">
		<?php echo $field->header; ?> 
	</label>
	<div class="controls" id="file-<?php echo $field->name; ?>">
		<div class="row-fluid">
			<?php if( ! empty($value)): ?>
				<div class="well well-small">
					<div class="spoiler-file-<?php echo $field->name; ?>">
						<?php 
						$attrs = array('target' => 'blank', 'class' => 'btn', 'id' => 'uploaded-' . $field->name);
						$title = UI::icon('file' ) . ' ' . __('View file');
						if($field->is_image( PUBLICPATH . $value)) 
						{
							$attrs['class'] .= ' popup';
							$attrs['data-title'] = 'false';
						}
						echo HTML::anchor(PUBLIC_URL . $value, $title, $attrs); ?>
						&nbsp;&nbsp;&nbsp;
						<label class="checkbox inline">
						<?php echo Form::checkbox( $field->name . '_remove', 1, FALSE, array('class' => 'remove-file-checkbox')); ?> <?php echo __('Remove file'); ?>
						</label>
						<hr />
					</div>
					<div class="spoiler-toggle" data-spoiler=".spoiler-file-<?php echo $field->name; ?>">
						<i class="icon-chevron-down spoiler-toggle-icon"></i> <?php echo __('Upload new file'); ?>
					</div>
				</div>
			<?php endif; ?>
			
			<div class="upload-new-cont <?php if( ! empty($value)): ?>spoiler<?php endif; ?> spoiler-file-<?php echo $field->name; ?>">
				<div class="file-upload btn">
					<span><?php echo __('Select file to upload'); ?></span>
					<?php echo Form::file( $field->name, array(
						'id' => $field->name, 'class' => 'upload-input'
					) ); ?>
				</div>
				
				<span class="help-block">
					<?php if(!empty($field->types)): ?>
					<?php echo __('Allowed types: :types', array(
					':types' => is_array($field->types) ? implode(', ', $field->types) : ''
					)); ?>.
					<?php endif; ?>
					<?php echo __('Max file size: :size', array(
					':size' => Text::bytes($field->max_size)
					)); ?>
				</span>
			</div>
		</div>
	</div>
</div>

<script>
	$(function() {
		var cont = $('#file-<?php echo $field->name; ?>');
		
		$('.remove-file-checkbox', cont).on('change', function() {
			if($(this).is(':checked')) {
				$('.upload-new-cont input', cont).attr('disabled', 'disabled');
				$('.spoiler-toggle,hr', cont).hide();
			} else {
				$('.spoiler-toggle,hr', cont).show();
				$('.upload-new-cont input', cont).removeAttr('disabled');
			}
		})
	})
</script>