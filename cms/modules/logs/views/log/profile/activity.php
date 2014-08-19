<?php if ( !empty($logs) ): ?>
<div class="panel-heading">
	<span class="panel-title"><?php echo __('Activity'); ?></span>
</div>

<div class="widget-article-comments panel-body no-border fade active in" id="profile-tabs-board">
	<?php foreach ($logs as $log): ?>
	<div class="comment">
		<?php echo Gravatar::load($log->email, 32, NULL, array(
			'class' => 'comment-avatar'
		)); ?>
		<div class="comment-body">
			<div class="comment-text">
				<div class="comment-heading">
					<?php echo HTML::anchor(Route::get('backend')->uri(array('controller' => 'users', 'action' => 'profile', 'id' => $log->user_id)), $log->username); ?>
					<span><?php echo Date::format($log->created_on, 'j F Y H:i'); ?></span>
				</div>
				<?php echo $log->message; ?>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>