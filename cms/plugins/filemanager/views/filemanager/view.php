<div class="page-header">
	<h1><?php echo __( 'View file - :file', array(':file' => $filesystem->getFilename()) ); ?></h1>
</div>

<?php echo Form::open(NULL, array('class' => 'form-horizontal')); ?>

	<?php echo Form::hidden('token', Security::token()); ?>

	<div class="control-group">
		<label class="control-label title" for="Filename"><?php echo __('File name'); ?></label>
		<div class="controls">
			<?php echo Form::input('file[name]', $filesystem->getFilename(), array(
				'class' => 'input-xlarge focus title', 'id' => 'Filename',
				'tabindex'	=> 1
			)); ?>
		</div>
	</div>

	<?php if($filesystem->isImage()): ?>
	<?php echo HTML::image(PUBLIC_URL . $filesystem->getRelativePath(), array(
		 'class' => 'img-polaroid'
	)) ;?>
	<?php else: ?>
	<?php echo Form::textarea('file[content]', $content, array(
		'id' => 'highlight_content', 'class' => 'span12', 'rows' => 200
	)); ?>
	<?php endif; ?>

	<div class="form-actions">
		<?php echo UI::actions($page_name); ?>
	</div>
<?php echo Form::close(); ?>