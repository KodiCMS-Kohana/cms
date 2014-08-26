<div class="panel">
	<?php if(count($types) > 0): ?>
	
	<?php foreach ($types as $title => $labels): ?>
	<div class="panel-heading">
		<span class="panel-title"><?php echo $title; ?></span>
	</div>
	<div class="panel-body">
		<?php foreach ($labels as $type => $label): ?>
		<button class="btn popup-btn" data-type="<?php echo $type; ?>">
			<?php echo $label; ?>
		</button>
		<?php endforeach; ?>
	</div>
	<?php endforeach; ?>
	<?php else: ?>
	<h2><?php echo __('No widgets'); ?></h2>
	<?php endif; ?>
</div>