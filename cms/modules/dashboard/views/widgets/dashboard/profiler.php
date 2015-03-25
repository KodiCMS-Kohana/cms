<div class="panel dashboard-widget panel-dark panel-info">
	<div class="panel-heading">
		<span class="panel-title" data-icon="bar-chart"><?php echo __('Profiler'); ?></span>
		<div class="panel-heading-controls">
			<button type="button" class="btn btn-default btn-xs remove_widget"><?php echo UI::icon('times'); ?></button>
		</div>
	</div>
	<div class="stat-panel">
		<div class="stat-row">
			<div class="stat-cell bg-dark-gray padding-sm text-xs text-semibold">
				<?php echo UI::icon('dot-circle-o'); ?>&nbsp;&nbsp;<?php echo __('Application Execution').' ('.$stats['count'].')' ?>
			</div>
		</div>
		<div class="stat-row">
			<div class="stat-counters">
				<?php foreach ($application_cols as $key): ?>
				<div class="stat-cell bg-dark-gray padding-sm text-xs text-semibold">
					<?php echo __(ucfirst($key)); ?>
				</div>
				<?php endforeach ?>
			</div>
		</div>
		<div class="stat-row">
			<div class="stat-counters bordered no-border-t text-center">
				<?php foreach ($application_cols as $key): ?>
				<div class="stat-cell col-xs-4 padding-sm no-padding-hr <?php echo $key ?>">
					<span class="text-bg"><?php echo number_format($stats[$key]['time'], 3) ?> <abbr title="seconds">s</abbr></span><br>
				</div>
				<?php endforeach ?>
			</div>
		</div>
		<div class="stat-row">
			<div class="stat-counters bordered no-border-t text-center">
				<?php foreach ($application_cols as $key): ?>
				<div class="stat-cell col-xs-4 padding-sm no-padding-hr <?php echo $key ?>">
					<span class="text-bg"><?php echo number_format($stats[$key]['memory'] / 1024, 2) ?> <abbr title="kilobyte">kB</abbr></span><br>
				</div>
				<?php endforeach ?>
			</div>
		</div>
		
	</div>
</div>