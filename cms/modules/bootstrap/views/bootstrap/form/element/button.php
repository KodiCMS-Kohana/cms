<?php if($element->get('dropdown') instanceof Bootstrap_Dropdown): ?>

	<?php echo Form::button(NULL, $element->get('body') . ' ' . Bootstrap_Dropdown::caret(), $attributes->as_array()); ?>
	<?php echo $element->get('dropdown'); ?>

<?php else: ?>
<?php echo $content; ?>
<?php endif; ?>
