<?php echo $form; ?>
<div class="panel panel-sidebar">
	<div class="panel-body">
		<?php foreach ($fields as $field): ?>
		<?php echo $field; ?>
		<?php endforeach; ?>
	</div>
	
	<div class="form-actions panel-footer">
		<button type="submit" class="btn btn-primary"><?php echo UI::icon('search'); ?> <?php echo __('Search'); ?></button>
		<?php echo HTML::anchor($form->action(), __('Cancel'), array('class' => 'btn btn-xs')); ?>
	</div>
</div> 
<?php echo Form::close(); ?>
