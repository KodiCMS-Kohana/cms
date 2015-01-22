<div class="panel dashboard-widget twitter-timeline-widget panel-info" data-id="<?php echo $widget->id; ?>">
	<div class="panel-heading handle">
		<span class="panel-title" data-icon="twitter"><?php echo $header; ?>&nbsp;</span>
		
		<div class="panel-heading-controls">
			<button type="button" class="btn btn-default btn-xs widget_settings"><?php echo UI::icon('cog'); ?></button>
			<button type="button" class="btn btn-default btn-xs remove_widget"><?php echo UI::icon('times'); ?></button>
		</div>
	</div>
	<?php if ($widget->widget_id === NULL): ?>
	<div class="note note-warning">
		<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need set Twitter widget ID'); ?>
	</div>
	<?php else: ?>
	<div class="panel-body padding-sm ">
		<?php echo HTML::anchor('', NULL, array(
			'class' => 'twitter-timeline',
			'data-widget-id' => $widget->widget_id,
			'height' => 200,
			'data-chrome' => 'nofooter noheader noborders transparent noscrollbar'
		)); ?>
		<script type="text/javascript">
		$(function() {
			window.twttr = (function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0],
					t = window.twttr || {};

				if (d.getElementById(id)) return;

				js = d.createElement(s);
				js.id = id;
				js.src = "https://platform.twitter.com/widgets.js";
				fjs.parentNode.insertBefore(js, fjs);
				t._e = [];
				t.ready = function(f) {
					t._e.push(f);
				};

				return t;
			}(document, "script", "twitter-wjs"));

			twttr.ready(function() {
				updateTwitterHeight($('.twitter-timeline-widget[data-id="<?php echo $widget->id; ?>"]'));
			});
			
			$('.twitter-timeline-widget[data-id="<?php echo $widget->id; ?>"]').on('resize_stop', function(e, gridster, ui) {
				updateTwitterHeight($(this));
			});

			function updateTwitterHeight(cont) {
				cont.find('.twitter-timeline')
					.prop('height', calculate_body_height)
					.css({
						width: '100%'
					});
			}
			function calculate_body_height() {
				var cont = $('.twitter-timeline-widget[data-id="<?php echo $widget->id; ?>"]');
				var heading = cont.find('.panel-heading');
				var h = cont.innerHeight() - heading.innerHeight() - 20;
				return h;
			}
		});
		</script>
	</div>
	<?php endif; ?>
</div>