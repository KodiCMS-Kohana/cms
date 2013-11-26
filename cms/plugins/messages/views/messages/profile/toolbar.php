<?php echo UI::button(__('Send message'), array(
	'class' => 'btn btn-warning popup fancybox.iframe',
	'href' => Route::url('backend', array('controller' => 'messages', 'action' => 'add')) . URL::query(array('to' => $user_id))
)); ?>