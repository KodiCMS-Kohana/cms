<h1><?php echo __( 'Polls results' ); ?></h1>

<div id="poll-container">
	<?php foreach ($polls as $poll): ?>
	<?php list($total_votes, $results) = $poll->get_results();?>
	<div class="poll-results">
		<h3><?php echo $poll->title(); ?></h3>
		<?php if(!empty($results)): ?>
		<dl class="graph">
			<?php foreach ($results as $result): ?>
			<dt class="bar-title"><?php echo $result[1]; ?></dt>
			<dd class="bar-container">
				<div style="width: <?php echo $result[3]; ?>%; display: block;" id="bar<?php echo $result[0]; ?>"><?php echo $result[3]; ?>%</div>
			</dd>
			
			<?php endforeach; ?>
		</dl>
		
		<p><?php echo __('Total Votes: :num', array(':num' => $total_votes)); ?></p>
		<?php else: ?>
		
		<h1><?php echo __('No votes'); ?></h1>	
		
		<?php endif; ?>
	</div>
	<?php endforeach; ?>
</div>