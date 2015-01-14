<script type="text/javascript">
$(function() {
	cms.filters.switchOn( 'message-content', '<?php echo Config::get('site', 'default_filter_id'); ?>');
});
</script>
<div class="panel">
	<?php echo Form::open(NULL, array('class' => Form::HORIZONTAL)); ?>
	<?php echo Form::token('token'); ?>
	<div class="panel-heading">
		<div class="form-group form-group-lg">
			<label class="control-label col-md-3"><?php echo __('Message title'); ?></label>
			<div class="col-md-9">
				<?php echo Form::input( 'title', NULL, array(
					'class' => 'form-control'
				) ); ?>
			</div>
		</div>

		<?php if ($to !== NULL): ?>
		<?php echo Form::hidden( 'to', $to ); ?>
		<?php else: ?>
		<br />
		
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Message to'); ?></label>
			<div class="col-md-9">
				<?php echo Form::input('to[]', Request::current()->query('to'), array(' autocomplete' => 'off', 'id' => 'messageTo')); ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<?php echo Form::textarea('content', NULL, array('id' => 'message-content')); ?>
	<div class="panel-footer form-actions">
		<?php echo UI::button(__('Send message'), array('class' => 'btn-lg btn-primary')); ?>
	</div>
	<?php echo Form::close(); ?>
</div>