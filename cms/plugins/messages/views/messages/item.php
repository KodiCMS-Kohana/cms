<div class="mail-info">
	<?php echo $from_user->gravatar(40, NULL, array('class' => 'avatar')); ?>
	<div class="from">
		<div class="name">
			<?php echo HTML::anchor(Route::get('backend')->uri(array(
				'controller' => 'users',
				'action' => 'profile',
				'id' => $message->from_user_id
			)), $message->author); ?>
		</div>

		<div class="email"><?php echo $from_user->email; ?></div>
	</div>

	<div class="date"><?php echo Date::format($message->created_on, 'j F Y H:i:s'); ?></div>
</div>
<div class="mail-message-body" style="border-bottom: 5px solid whitesmoke;">
	<?php echo $message->text; ?>
</div>