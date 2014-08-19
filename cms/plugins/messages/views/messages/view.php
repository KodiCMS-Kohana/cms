<script type="text/javascript">
	var MESSAGE_ID = <?php echo $message->id; ?>;

	$(function() {
		cms.filters.switchOn( 'message-conent', '<?php echo Config::get('site', 'default_filter_id'); ?>');
	});
</script>
<div class="page-mail">
	<div class="mail-container-header no-margin-vr">
		<small><?php echo __('Subject'); ?>:</small> <?php echo $message->title; ?>
	</div>
	
	<div class="mail-controls clearfix">
		<div class="btn-toolbar wide-btns" role="toolbar">
			<?php echo UI::button(UI::icon('chevron-left'), array(
				'href' => Route::get('backend')->uri(array('controller' => 'messages')),
				'class' => 'btn-go-back'
			)); ?>
			
			<?php echo UI::button(NULL, array(
				'icon' => UI::icon('trash-o'),
				'class' => 'btn btn-confirm btn-remove'
			)); ?>
		</div>
	</div>
	
	<?php foreach ($messages as $msg): ?>
	<?php echo $tpl->set('message', $msg)->set('from_user', ORM::factory('user', $message->from_user_id)); ?>
	<?php endforeach; ?>
	
	<?php echo $tpl->set('message', $message)->set('from_user', $from_user); ?>

	<div class="message-details-reply">
		<?php echo Form::open(); ?>
		<h4><?php echo __('Answer'); ?></h4>
		<?php echo Form::hidden('parent_id', $message->id); ?>
		<?php echo Form::textarea('content', NULL, array('id' => 'message-conent')); ?>
		<br />
		<?php echo UI::button(__('Send message'), array('class' => 'btn btn-primary pull-right')); ?>
		<?php echo Form::close(); ?>
	</div>
	
	<div class="clearfix"></div>
</div>