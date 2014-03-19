<?php 
$config = Kohana::$config->load('mimes')->as_array(); 
$mimes = array();
foreach($config as $ext => $mime)
{
	$mimes[$ext] = $ext;
}
?>
<div class="widget-content ">
	<div class="control-group">
		<label class="control-label"><?php echo __( 'Allowed file types' ); ?></label>
		<div class="controls">
			<?php echo Form::select( 'types[]', $mimes, (array) $field->types, array('class' => 'input-block-level', 'id' => 'allowed_types')); ?>
			<span class="label file-types-label" data-types="bmp,gif,jpg,png,tif"><?php echo __('Image types'); ?></span>
			<span class="label file-types-label" data-types="doc,docx,xls,txt,pdf"><?php echo __('Document types'); ?></span>
			<span class="label file-types-label" data-types="rar,zip,tar,gz,7z"><?php echo __('Archive types'); ?></span>
			<span class="label file-types-label" data-types="mp3,wav"><?php echo __('Audio types'); ?></span>
			<script>
			$(function() {
				$('.file-types-label').click(function() {
					$("#allowed_types").select2('val', $(this).data('types').split(','));
				});
			})
			</script>
		</div>
	</div>

	<hr />
	<div class="control-group">
		<label class="control-label"><?php echo __( 'Max file size' ); ?></label>
		<div class="controls">
			<?php echo Form::input('max_size', $field->max_size, array('class' => 'input-small', 'id' => 'max_size')); ?> (<?php echo Text::bytes($field->max_size); ?>)
			<span class="label file-size-label" data-size="<?php echo NUM::bytes('100K'); ?>">100k</span>
			<span class="label file-size-label" data-size="<?php echo NUM::bytes('1MiB'); ?>">1Mib</span>
			<span class="label file-size-label" data-size="<?php echo NUM::bytes('5MiB'); ?>">5Mib</span>
			<span class="label file-size-label" data-size="<?php echo NUM::bytes('10MiB'); ?>">10Mib</span>
			<script>
			$(function() {
				$('.file-size-label').click(function() {
					$("#max_size").val($(this).data('size'));
				});
			})
			</script>
		</div>
	</div>
</div>