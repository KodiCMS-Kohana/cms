<?php if ( !empty($logs) ): ?>

<h3 class="page-header"><?php echo __('Activity'); ?></h3>

<ul class="chat-box">
	<?php foreach ($logs as $log): ?>
	<li class="arrow-box-left gray log-level-<?php echo $log->level; ?>">
		<div class="avatar">
			<?php echo Gravatar::load($log->email, 32); ?>
		</div>
		<div class="info">
			<span class="name">
				<span class="label"><?php echo Log::level($log->level); ?></span>&nbsp;&nbsp;
				<?php echo $log->message; ?>
			</span>
		</div>
		<span class="time"><i class="icon-time"></i> <?php echo Date::format($log->created_on, 'j F Y H:i:s'); ?></span>
		<div class="clearfix"></div>
	</li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>