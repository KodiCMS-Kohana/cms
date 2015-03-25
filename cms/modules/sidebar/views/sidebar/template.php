<?php echo $form; ?>
<div class="panel-sidebar">
	<div class="panel-heading panel-toggler">
		<span class="panel-title"><?php echo __('Filter'); ?></span>
	</div>
	<div class="panel-body panel-spoiler">
		<?php foreach ($fields as $field): ?>
			<div class="col-sm-4">
				<?php echo $field; ?>
			</div>
		<?php endforeach; ?>
		
		<hr class="panel-wide visible-xs" />
		
		<button type="submit" class="btn btn-primary" data-icon="search"><?php echo __('Search'); ?></button>
		&nbsp;&nbsp;&nbsp;
		<?php echo HTML::anchor($form->action(), __('Cancel'), array('class' => 'btn btn-xs btn-outline btn-rounded', 'data-icon' => 'ban')); ?>
	</div>
</div> 
<?php echo Form::close(); ?>
