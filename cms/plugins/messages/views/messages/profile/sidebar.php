<a href="<?php echo Route::get('backend')->uri(array('controller' => 'messages')); ?>" class="list-group-item">
	<?php echo UI::icon('envelope-o'); ?>&nbsp;&nbsp;<?php echo __('Messages'); ?>
	<span class="badge badge-info"><?php echo $new_messages; ?></span>
</a>