<div id="message_<?php echo $message->id; ?>" class="widget-content widget-no-border-radius">
	<?php echo UI::label($message->author); ?> <?php echo UI::label($message->created(), ''); ?>
	<hr />
	<div class="<?php if( $message->is_read() ): ?>muted<?php endif; ?>">
	<?php echo $message->text; ?>
	</div>
</div>