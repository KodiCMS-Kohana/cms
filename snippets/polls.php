<?php
$poll = polls::instance()->get_poll( 1 );
?>

<div id = "poll-container">
	<?php if ( !$poll->is_voted() OR !$poll->only_unique_ip() ): ?>
		<form id='poll_<?php echo $poll->id(); ?>' action = "/send_poll" method="post">
			<input type="hidden" name="poll_id" value="<?php echo $poll->id(); ?>" />
			<p><?php echo $poll->title(); ?></p>
			<p>
				<?php foreach ( $poll->options() as $id => $title ): ?>
					<input type="radio" name="option_id" value="<?php echo $id; ?>" id="opt<?php echo $id; ?>" />
					<label for='opt<?php echo $id; ?>'><?php echo $title; ?></label><br />
				<?php endforeach; ?>

				<?php if ( Plugin::isEnabled( 'captcha' ) ): ?>
				<div class="fields-line">
					<p>
						<img src="<?php echo CMS_URL; ?>captcha.jpg" alt="<?php echo __( 'Captcha code' ); ?>" id="captcha_image" title="<?php echo __( 'Type text that present on this image.' ); ?>" />
					</p>
					<p>
						<label><?php echo __( 'Image text' ); ?>:</label>
						<span><input class="input-text" type="text" name="captcha" value="" tabindex="3" autocomplete="off" /></span>
					</p>
				</div>
			<?php endif; ?>
			<input type = "submit" value = "<?php echo __( 'Голосовать' ); ?>" />
			</p>
		</form>
	<?php else: ?>
		<?php list($total_votes, $results) = $poll->get_results(); ?>
		<div class="poll-results">
			<h3><?php echo $poll->title(); ?></h3>
			<dl class="graph">
				<?php foreach ( $results as $result ): ?>
					<dt class="bar-title"><?php echo $result[1]; ?></dt>
					<dd class="bar-container">
						<div style="width: <?php echo $result[3]; ?>%; display: block;" id="bar<?php echo $result[0]; ?>"><?php echo $result[3]; ?>%</div>
					</dd>

				<?php endforeach; ?>
			</dl>
		</div>
	<?php endif; ?>
</div>