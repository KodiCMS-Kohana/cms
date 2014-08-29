<div class="panel dashboard-widget twitter-timeline-widget panel-info" data-id="<?php echo $widget->id; ?>">
	<div class="panel-heading handle">
		<span class="panel-title" data-icon="twitter"><?php echo $header; ?>&nbsp;</span>
		
		<div class="panel-heading-controls">
			<button type="button" class="btn btn-default btn-xs widget_settings"><?php echo UI::icon('cog'); ?></button>
			<button type="button" class="btn btn-default btn-xs remove_widget"><?php echo UI::icon('times'); ?></button>
		</div>
	</div>
	<?php if($widget->widget_id === NULL): ?>
	<div class="note note-warning">
		<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need set Twitter widget ID'); ?>
	</div>
	<?php else: ?>
	<div class="panel-body padding-sm ">
		<?php echo HTML::anchor('', NULL, array(
			'class' => 'twitter-timeline',
			'data-widget-id' => $widget->widget_id,
			'height' => $widget->height,
			'data-chrome' => 'nofooter noheader noborders transparent noscrollbar'
		)); ?>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</div>
	<?php endif; ?>
</div>