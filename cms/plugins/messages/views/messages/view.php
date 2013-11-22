<script>
	var MESSAGE_ID = <?php echo $message->id; ?>;
</script>

<div class="widget">
	<div class="widget-header">
		<h3><small><?php echo __('Subject'); ?>:</small> <?php echo $message->title; ?></h3>
	</div>
	
	<?php echo $tpl->set('message', $message); ?>
	
	<div class="widget-content widget-no-border-radius">
		<?php echo Form::open(); ?>
		<h4><?php echo __('Answer'); ?></h4>
		<?php echo Form::hidden('parent_id', $message->id); ?>
		<?php echo Form::textarea('content', NULL, array('id' => 'message-conent')); ?>
		<script>
		$(function() {
			cms.filters.switchOn( 'message-conent', '<?php echo Config::get('site', 'default_filter_id'); ?>');
		});
		</script>
		<br />
		<?php echo UI::button(__('Send message')); ?>
		<?php echo Form::close(); ?>
	</div>
	
	<div id="messages">
	<?php foreach ($messages as $msg): ?>
	<?php if($msg->is_read == Model_API_Message::STATUS_NEW): ?>
	<?php Api::post('user-messages.mark_read', array(
			'id' => $msg->id, 'uid' => AuthUser::getId()
		)); ?>
	<?php endif; ?>
	<?php echo $tpl->set('message', $msg); ?>
	<?php endforeach; ?>
</div>