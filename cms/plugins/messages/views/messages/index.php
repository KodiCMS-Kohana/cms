<div class="page-mail">
	<div class="mail-container-header  no-margin-vr">
		<?php echo __('Messages'); ?>
	</div>
	
	<div class="mail-controls clearfix">
		<div class="btn-toolbar" role="toolbar">
			<?php echo UI::button(__('Send message'), array(
				'href' => Route::get('backend')->uri(array('controller' => 'messages', 'action' => 'add')), 'icon' => UI::icon('envelope-o'),
				'class' => 'btn-primary'
			)); ?>
			
			<button type="button" class="btn btn-default btn-check-new"><i class="fa fa-repeat"></i></button>
			
			<?php if (count($messages) > 0): ?>
				<button type="button" class="btn btn-remove btn-danger"><i class="fa fa-trash-o"></i></button>
			<?php endif; ?>
		</div>
	</div>
	
	<div id="messages-container">
		<?php echo View::factory('messages/messages', array(
			'messages' => $messages
		)); ?>
	</div>
</div>