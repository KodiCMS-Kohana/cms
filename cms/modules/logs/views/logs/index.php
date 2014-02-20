<div class="row-fluid">
	<div class="span9">
		<div class="widget widget-nopad">
			<div class="widget-header">

			</div>
			<div class="widget-content">
				<table class="table table-striped table-hover">
					<colgroup>
						<col width="150px" />
						<col width="100px" />
						<col />
						<col width="200px" />
						<col width="150px" />
					</colgroup>
					<thead>
						<tr>
							<th><?php echo __('Сreated on'); ?></th>
							<th><?php echo __('Log level'); ?></th>
							<th><?php echo __('Log message'); ?></th>
							<th><?php echo __('Log url'); ?></th>
							<th><?php echo __('User'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($logs as $log): ?>
						<tr class="item">
							<td class="log-date">
								<?php echo Date::format($log->created_on, 'j F y H:i'); ?>
							</td>
							<td class="log-level">
								<?php echo UI::label($log->level()); ?>
							</td>
							<td class="log-message">
								<?php echo $log->message; ?>
							</td>
							<td class="log-url">
								<?php echo $log->url(); ?>
							</td>
							<td class="log-user">
								<?php echo $log->user(); ?> <?php echo $log->ip(); ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php echo $pager; ?>
	</div>
	<div class="span3">
		<?php echo $sidebar; ?>
	</div>
</div>



