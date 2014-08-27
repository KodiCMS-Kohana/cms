<div class="panel dashboard-widget panel-info panel-body-colorful panel-dark" data-id="<?php echo $widget->id; ?>">
	<div class="panel-body text-lg handle">
		<button type="button" class="close remove_widget">×</button>

		<i class="fa fa-calendar fa-2x"></i>&nbsp;&nbsp;<span class="time-container"></span>
	</div>
</div>
<script type="text/javascript">
$(function(){
    update_time();
});

var update_time = function () {
    date = moment(new Date());
    $('.time-container').html(date.format('Do MMM, dddd, YYYY, HH:mm'));
	setInterval(update_time, 60000);
};
</script>