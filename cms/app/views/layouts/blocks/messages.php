<script>
$(function() {
<?php foreach ( $messages as $type => $mess ): ?>
	<?php foreach ( $mess as $message ): ?>
		$.jGrowl("<?php echo addslashes($message); ?>");
	<?php endforeach; ?>
<?php endforeach; ?>
})
</script>