<?php echo UI::button(__('Save'), array(
	'class' => 'btn-success btn-save btn-lg', 
	'icon' => UI::icon('retweet'),
	'name' => 'continue',
	'data-hotkeys' => 'ctrl+s'
)); ?>
&nbsp;&nbsp;
<?php echo UI::button(__('Save and Close'), array(
	'class' => 'btn-save-close btn-default hidden-xs', 
	'icon' => UI::icon('check'),
	'name' => 'commit',
	'data-hotkeys' => 'ctrl+shift+s'
)); ?>
&nbsp;&nbsp;&nbsp;&nbsp;

<?php echo HTML::anchor($uri, UI::hidden(__('Cancel')), array('data-icon' => 'ban', 'class' => 'btn btn-close btn-sm btn-outline')); ?>