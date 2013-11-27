<?php if ( !empty($logs) ): ?>

<h3 class="page-header"><?php echo __('Activity'); ?></h3>

<?php foreach ($logs as $log): ?>
<div class="feed-item feed-item-idea log-level-<?php echo $log->level; ?>">
	<div class="feed-icon">
		<i class="icon-arrow-right"></i>
	</div>

	<div class="feed-subject">
		<p><?php echo $log->message; ?></p>
	</div>

	<div class="feed-actions">
		<small class="muted pull-right"><i class="icon-time"></i> <?php echo Date::format($log->created_on, 'j F Y H:i:s'); ?></small>
	</div>
</div>
<?php endforeach; ?>
<?php endif; ?>