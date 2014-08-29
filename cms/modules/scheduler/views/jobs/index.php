<div class="panel">
	<div class="panel-heading">
		<?php if (Acl::check('jobs.add')): ?>
		<?php echo UI::button(__('Add job'), array(
			'href' => Route::get('backend')->uri(array('controller' => 'jobs', 'action' => 'add')), 
			'icon' => UI::icon('plus'),
			'data-hotkeys' => 'ctrl+a',
			'class' => 'btn-primary'
		)); ?>
		<?php endif; ?>
	</div>
	
	<table class="table table-primary table-striped table-hover">
		<colgroup>
			<col />
			<col width="150px" />
			<col width="100px" />
			<col width="170px" />
			<col width="170px" />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Job name'); ?></th>
				<th class="hidden-xs"><?php echo __('Job function'); ?></th>
				<th><?php echo __('Status'); ?></th>
				<th class="hidden-xs"><?php echo __('Last run'); ?></th>
				<th class="hidden-xs"><?php echo __('Next run'); ?></th>
				<th class="text-right"><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($jobs as $job): ?>
				<tr class="item">
					<td class="job-name">
						<?php if ( Acl::check('jobs.edit')): ?>
						<?php echo HTML::anchor(Route::get('backend')->uri(array(
							'controller' => 'jobs',
							'action' => 'edit',
							'id' => $job->id
						)), $job->name); ?>
						<?php else: ?>
						<?php echo UI::icon('lock'); ?> <?php echo $job->name; ?>
						<?php endif; ?>
					</td>
					<td class="job-function hidden-xs">
						<?php echo UI::label($job->job); ?>
					</td>
					<td class="job-status">
						<?php echo $job->status(); ?>
					</td>
					<td class="job-last-run hidden-xs">
						<?php echo Date::format($job->date_last_run, 'j/m/Y H:i:s'); ?>
					</td>
					<td class="job-interval hidden-xs">
						<?php echo Date::format($job->date_next_run, 'j/m/Y H:i:s'); ?>
					</td>
					<td class="actions text-right">
						<?php if ( Acl::check('jobs.delete')): ?>
						<?php echo UI::button(NULL, array(
							'href' => Route::get('backend')->uri(array(
								'controller' => 'jobs',
								'action' => 'delete',
								'id' => $job->id
							)), 
							'icon' => UI::icon('times fa-inverse'),
							'class' => 'btn btn-xs btn-danger btn-confirm'
						)); ?>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php echo $pager; ?>