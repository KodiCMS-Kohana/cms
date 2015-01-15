<div class="panel dashboard-widget panel-info panel-dark" id="widget-activity">
	<div class="panel-heading">
		<span class="panel-title" data-icon="bullhorn"><?php echo __('Activity'); ?></span>
		<div class="panel-heading-controls">
			<button type="button" class="btn btn-default btn-xs remove_widget"><?php echo UI::icon('times'); ?></button>
		</div>
	</div>
	<div class="widget-comments panel-body no-padding-vr">
		<?php foreach ($logs as $log): ?>
		<div class="comment">
			<?php echo Gravatar::load($log->email, 32, NULL, array(
				'class' => 'comment-avatar'
			)); ?>
			<div class="comment-body">
				<div class="comment-by">
					<?php echo HTML::anchor(Route::get('backend')->uri(array('controller' => 'users', 'action' => 'profile', 'id' => $log->user_id)), $log->username); ?>
					<span><?php echo Date::format($log->created_on, 'j F Y H:i'); ?></span>
				</div>
				<div class="comment-text">

					<?php echo $log->message; ?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>

<script type="text/javascript">
$(function(){
	$('#widget-activity').on('resize', function(e, gridster, ui) {
		updateScroll();
	});
	
	initScroll();
});

function initScroll() {
	$('#widget-activity .panel-body').slimScroll({
		height: calculate_body_height
	});
}

function updateScroll() {
	$('#widget-activity .panel-body').slimScroll({destroy: true});
	initScroll();
}

function calculate_body_height() {
	var $cont = $('#widget-activity');
	var heading = $cont.find('.panel-heading');
	var h = $cont.innerHeight() - heading.innerHeight();
	return h-5;
}
</script>