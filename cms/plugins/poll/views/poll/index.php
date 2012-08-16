<h1><?php echo __( 'Polls results' ); ?></h1>

<div id="poll-container">
	<?php foreach ( $polls as $poll ): ?>
		<?php list($total_votes, $results) = $poll->get_results(); ?>
		<div class="poll-results well">
			<h3><?php echo $poll->title(); ?></h3>

			<?php if ( !empty( $results ) ): ?>
				<?php foreach ( $results as $result ): ?>
					<div class="poll-result">
						<div class="bar-title"><?php echo $result[1]; ?></div>
						<div class="bar-container progress">
							<div class="bar" style="width: <?php echo $result[3]; ?>%; display: block;" id="bar<?php echo $result[0]; ?>"><?php echo $result[3]; ?>%</div>
						</div>
						<div class="clear"></div>
					</div>
				<?php endforeach; ?>
				<p><?php echo __( 'Total Votes: :num', array( ':num' => $total_votes ) ); ?></p>
			<?php else: ?>

				<h1><?php echo __( 'No votes' ); ?></h1>	

			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>