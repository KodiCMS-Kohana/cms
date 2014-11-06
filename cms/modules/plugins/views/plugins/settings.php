<?php echo Form::open(NULL, array('class' => Form::HORIZONTAL)); ?>
	<?php echo Form::hidden('token', Security::token()); ?>
	<div class="panel">
		<?php echo $content; ?>
		<div class="form-actions panel-footer">
			<?php echo UI::actions('plugins'); ?>
		</div>
	</div>
<?php echo Form::close(); ?>