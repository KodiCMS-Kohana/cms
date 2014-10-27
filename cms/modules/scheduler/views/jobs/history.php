<div class="panel no-margin-b">
	<div class="panel-heading">
		<spna class="panel-title"><?php echo __('Job history'); ?></spna>
	</div>
	<table class="table table-primary table-striped table-hover">
		<colgroup>
			<col width="200px" />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Job run time'); ?></th>
				<th><?php echo __('Status'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($history as $log): ?>
				<tr class="item">
					<td class="log-run-time">
						<?php echo Date::format($log->created_on, 'j F Y H:i:s'); ?>
					</td>
					<td class="job-status">
						<?php echo $log->status(); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
