<div id="message_<?php echo $message->id; ?>" class="widget-content widget-no-border-radius <?php if(AuthUser::getId() == $message->from_user_id): ?>widget-content-bg<?php endif; ?>">
	<h4><?php echo HTML::anchor(Route::url('backend', array(
			'controller' => 'users',
			'action' => 'profile',
			'id' => $message->from_user_id
		)), $message->author); ?> <small><?php echo Date::format($message->created_on, 'j F Y H:i:s'); ?></small></h4> 
	<div class="<?php if( $message->is_read == Model_API_Message::STATUS_READ ): ?>muted<?php endif; ?>">
	<?php echo $message->text; ?>
	</div>
</div>