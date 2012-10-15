<script>
$(function() {
<?php foreach ( $messages as $type => $mess ): ?>
		var MESSAGE_<?php echo strtoupper( $type ); ?> = {};
	<?php foreach ( $mess as $field => $message ): ?>
		MESSAGE_<?php echo strtoupper( $type ); ?>['<?php echo $field; ?>'] = '<?php echo addslashes($message); ?>';
		$.jGrowl("<?php echo addslashes($message); ?>");
	<?php endforeach; ?>
<?php endforeach; ?>
	
	for(error in MESSAGE_ERRORS) {
		var msg = '<span class="help-inline">' + MESSAGE_ERRORS[error] + '</span>';
		var input = $('input[name="' + error + '"]')
			.after(msg)
			.parentsUntil( '.control-group' )
			.parent()
			.addClass('error');
	}
})
</script>