<div class="panel dashboard-widget panel-info panel-body-colorful panel-dark" data-id="<?php echo $widget->id; ?>">
	<div class="panel-body text-lg handle">
		<button type="button" class="close remove_widget">×</button>

		<i class="fa fa-calendar fa-2x"></i>&nbsp;&nbsp;<span class="time-container"></span>
	</div>
</div>
<script type="text/javascript">
$(function(){
	var update_dashboard_calendar = function () {
		$('.time-container').html(moment(new Date()).format('Do MMM, dddd, YYYY, HH:mm'));
		setTimeout(function () { update_dashboard_calendar(); }, 60000);
	};

    update_dashboard_calendar();
});
</script>