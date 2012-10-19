<?php foreach ( $messages as $type => $mess ): ?>
	var MESSAGE_<?php echo strtoupper( $type ); ?> = <?php echo json_encode($mess); ?>;
<?php endforeach; ?>