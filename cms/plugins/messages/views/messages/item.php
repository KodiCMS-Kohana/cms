<div id="message_<?php echo $message->id; ?>" class="widget-content widget-no-border-radius">
	<?php echo UI::label($message->author); ?> <?php echo UI::label(Date::format($message->created_on), ''); ?>
	<hr />
	<div class="<?php if( $message->is_read == Model_API_Message::STATUS_READ ): ?>muted<?php endif; ?>">
	<?php echo $message->text; ?>
	</div>
</div>