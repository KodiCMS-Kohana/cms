<div class="form-group">
	<label class="control-label col-md-3" for="<?php echo $field->name; ?>">
		<?php echo $field->header; ?> 
	</label>
	<div class="col-md-9" id="file-<?php echo $field->name; ?>">
		<div class="row-fluid">
			<?php if( ! empty($value)): ?>
			<div class="panel">
				<div class="panel-heading panel-toggler" data-icon="chevron-down">
					<span class="panel-title"><?php echo __('Upload new file'); ?></span>
				</div>
				<div class="panel-body panel-spoiler">
					<?php 
					$attrs = array('target' => 'blank', 'class' => array('btn btn-default'), 'id' => 'uploaded-' . $field->name);
					$title = UI::icon('file' ) . ' ' . __('View file');
					if($field->is_image( PUBLICPATH . $value)) 
					{
						$attrs['class'][] = 'popup';
						$attrs['data-title'] = 'false';
					}
					echo HTML::anchor(PUBLIC_URL . $value, $title, $attrs); ?>
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
				<?php echo Form::file($field->name, array(
					'id' => $field->name, 'class' => 'form-control upload-input'
				)); ?>
				
				<p class="help-block">
					<?php if(!empty($field->types)): ?>
					<?php echo __('Allowed types: :types', array(
					':types' => is_array($field->types) ? implode(', ', $field->types) : ''
					)); ?>.
					<?php endif; ?>
					<?php echo __('Max file size: :size', array(
					':size' => Text::bytes($field->max_size)
					)); ?>
				</p>
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
				$('.panel-toggler,hr', cont).hide();
			} else {
				$('.panel-toggler,hr', cont).show();
				$('.upload-new-cont input', cont).removeAttr('disabled');
			}
		})
	})
</script>