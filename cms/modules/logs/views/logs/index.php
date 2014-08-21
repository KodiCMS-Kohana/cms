<?php echo $sidebar; ?>
<div class="panel">
	<table id="logs-table" class="table table-primary table-hover">
		<colgroup>
			<col width="150px" />
			<col width="100px" />
			<col />
			<col width="150px" />
		</colgroup>
		<thead>
			<tr>
				<th class="log-date"><?php echo __('Сreated on'); ?></th>
				<th class="log-level hidden-xs"><?php echo __('Log level'); ?></th>
				<th class="log-url hidden-xs"><?php echo __('Log url'); ?></th>
				<th class="log-user hidden-xs"><?php echo __('User'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($logs as $log): ?>
				<?php $class = Text::alternate('even', 'odd'); ?>
				<tr class="item <?php echo $class; ?>">
					<td class="log-date">
						<?php echo Date::format($log->created_on, 'j F y H:i'); ?>
					</td>
					<td class="log-level hidden-xs">
						<?php echo UI::label($log->level()); ?>
					</td>
					<td class="log-url hidden-xs">
						<?php echo $log->url(); ?>
					</td>
					<td class="log-user hidden-xs">
						<?php echo $log->user(); ?> <?php echo $log->ip(); ?>
					</td>
				</tr>
				<tr class="item-message <?php echo $class; ?>">
					<td class="log-message" colspan="4"><?php echo $log->message; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php echo $pager; ?>



