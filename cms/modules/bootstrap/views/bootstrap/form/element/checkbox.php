<?php if( $element->label instanceof Bootstrap_Form_Element_Label ): ?>
<label<?php echo $element->label->attributes(); ?>>
	<?php echo $content; ?> <?php echo $element->label->title; ?>
</label>
<?php else: echo $content; endif; ?>

<?php if( isset($element->help_text)) echo $element->help_text; ?>