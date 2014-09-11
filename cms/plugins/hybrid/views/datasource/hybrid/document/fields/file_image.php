<div class="form-group" id="file-<?php echo $field->name; ?>">
	<label class="<?php echo Arr::get($form, 'label_class'); ?>" for="<?php echo $field->name; ?>">
		<?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?>
	</label>
	<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
		<div class="panel">
			<?php if( !empty($value)): ?>
			<div class="panel-heading panel-toggler" data-icon="chevron-down">
				<span class="panel-title"><?php echo __('Upload new file'); ?></span>
			</div>
			<?php endif; ?>
			<div class="panel-body padding-sm <?php if( ! empty($value)): ?>panel-spoiler<?php endif; ?>">
				<div class="form-group">
					<div class="col-xs-5">
						<?php echo Form::file($field->name, array(
							'id' => $field->name, 'class' => 'form-control upload-input'
						)); ?>
					</div>
					<div class="col-xs-7">
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

				<?php if(!empty($value)): ?>
				<hr class="no-margin-b"/>
				<?php endif; ?>
			</div>

			<?php if (!empty($value)): ?>
			<div class="panel-body padding-sm">
				<?php echo HTML::anchor(PUBLIC_URL . $value, HTML::image(Image::cache($value, 100, 100, Image::HEIGHT)), array(
					'class' => 'popup thumbnail pull-left no-margin-b', 'data-title' => __('View file')
				)); ?>
				&nbsp;&nbsp;&nbsp;
				<div class="checkbox-inline">
					<label>
						<?php echo Form::checkbox( $field->name . '_remove', 1, FALSE, array('class' => 'remove-file-checkbox')); ?> <?php echo __('Remove file'); ?>
					</label>
				</div>
			</div>
			<?php endif; ?>
			
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		var $cont = $('#file-<?php echo $field->name; ?>');
		
		$('.remove-file-checkbox', $cont).on('change', function() {
			var $self = $(this);

			if($(this).is(':checked')) {
				$('.upload-new-cont input', $cont).attr('disabled', 'disabled');
				$('.panel-toggler', $cont).hide();
				$('.panel-spoiler', $cont).show();
				$('.thumbnail', $cont).hide();
			} else {
				$('.panel-toggler', $cont).show();
				$('.upload-new-cont input', $cont).removeAttr('disabled');
				$('.panel-spoiler', $cont).hide();
				$('.thumbnail', $cont).show();
			}
		})
	})
</script>