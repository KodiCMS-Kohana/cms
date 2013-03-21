<?php if($element->title)
	echo HTML::anchor('#', $element->title . ' ' . Bootstrap_Dropdown::caret(), array(
		'class' => 'dropdown-toggle',
		'data-toggle' => 'dropdown'
	));
?>
<ul<?php echo $attributes; ?>>
	<?php foreach ($elements as $element): ?>
	<?php echo $element; ?>
	<?php endforeach; ?>
</ul>