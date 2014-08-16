<?php echo Form::open(NULL, array('class' => Bootstrap_Form::HORIZONTAL)); ?>
	<?php echo Form::hidden('token', Security::token()); ?>
	<div class="panel">
		<div class="panel-body">
		<?php echo $content; ?>
		</div>
		<div class="form-actions panel-footer">
			<?php echo UI::actions('plugins'); ?>
		</div>
	</div>
<?php echo Form::close(); ?>