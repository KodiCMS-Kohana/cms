<div class="panel tabbable">
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('General information'); ?></span>
	</div>
	<div class="panel-body">
		<table class="table table-striped">
			<colgroup>
				<col width="200px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><?php echo __('CMS name') ?></th>
					<td><?php echo CMS_NAME; ?></td>
				</tr>
				<tr>
					<th><?php echo __('CMS Version') ?></th>
					<td><?php echo CMS_VERSION; ?>&nbsp;&nbsp;&nbsp;<?php echo HTML::anchor(Route::get('backend')->uri(array(
						'controller' => 'update'
					)), __('Check updates'), array('class' => 'btn btn-xs btn-info'));?></td>
				</tr>
				<tr>
					<th><?php echo __('PHP Version') ?></th>
					<td><?php echo PHP_VERSION; ?></td>
				</tr>
				<tr>
					<th><?php echo __('Kohana version') ?></th>
					<td>v<?php echo Kohana::VERSION; ?> <strong><?php echo Kohana::CODENAME; ?></strong></td>
				</tr>
				<tr>
					<th><?php echo __('Kohana enviroment') ?></th>
					<td><?php echo Arr::get($_SERVER, 'KOHANA_ENV'); ?></td>
				</tr>
				<tr>
					<th><?php echo __('Server host') ?></th>
					<td><?php echo Arr::get($_SERVER, 'HTTP_HOST'); ?></td>
				</tr>
				<tr>
					<th><?php echo __('Web server') ?></th>
					<td><?php echo Arr::get($_SERVER, 'SERVER_SOFTWARE'); ?></td>
				</tr>
				<tr>
					<th><?php echo __('Cache driver') ?></th>
					<td><?php echo Cache::$default; ?></td>
				</tr>
				<tr>
					<th><?php echo __('MySQL driver') ?></th>
					<td><?php echo DB_TYPE; ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<?php if (Acl::check('system.phpinfo') AND function_exists('phpinfo')): ?>
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('PHP info'); ?></span>
	</div>
	<div class="panel-body">
		<iframe src="<?php echo URL::site(Route::get('backend')->uri(array(
			'controller' => 'system',
			'action' => 'phpinfo'
		))); ?>" width="100%" height="500px" id="phpinfo" style="border: 0"></iframe>
	</div>
	<?php endif; ?>

	<?php Observer::notify('view_system_information'); ?>
</div>