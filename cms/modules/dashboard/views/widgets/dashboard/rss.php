<div class="panel dashboard-widget rss-feed-widget panel-info panel-dark" data-id="<?php echo $widget->id; ?>">
	<div class="panel-heading handle">
		<span class="panel-title" data-icon="rss"><?php echo empty($header) ? $feed_title : $header; ?>&nbsp;</span>
		
		<div class="panel-heading-controls">
			<button type="button" class="btn btn-default btn-xs widget_settings"><?php echo UI::icon('cog'); ?></button>
			<button type="button" class="btn btn-default btn-xs remove_widget"><?php echo UI::icon('times'); ?></button>
		</div>
	</div>
	<?php if(empty($rss_url)): ?>
	<div class="note note-warning">
		<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need set RSS feed url'); ?>
	</div>
	<?php else: ?>
	<div class="panel-body media-list">
		<?php foreach ($items as $item): ?>
		<div class="media">
			<div class="media-body">
				<a href="<?php echo $item['link']; ?>" target="blank" class="media-heading"><?php echo $item['title']; ?></a><br />
				<div class="media-description">
					<?php echo $item['description']; ?>
					&nbsp;&nbsp;·&nbsp;&nbsp;
					<?php echo Date::format($item['pubDate']); ?>
				</div>
			</div>
		</div>
		<hr class="panel-wide" />
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
</div>
<script type="text/javascript">
$(function(){$('.rss-feed-widget[data-id="<?php echo $widget->id; ?>"] .media-list').slimScroll({height:<?php echo $widget->height; ?>});})
</script>