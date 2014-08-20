<?php echo UI::button(__('Save and Close'), array(
	'class' => 'btn btn-save-close', 
	'icon' => UI::icon('check'),
	'name' => 'commit',
	'data-hotkeys' => 'ctrl+shift+s'
)); ?>
&nbsp;&nbsp;
<?php echo UI::button(__('Save'), array(
	'class' => 'btn btn-success btn-save btn-lg', 
	'icon' => UI::icon('retweet'),
	'name' => 'continue',
	'data-hotkeys' => 'ctrl+s'
)); ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo HTML::anchor($uri, __('Cancel'), array('data-icon' => 'ban', 'class' => 'btn btn-sm btn-outline')); ?>