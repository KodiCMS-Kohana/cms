<div class="panel">
	<div class="panel-heading">
		<?php echo UI::button(__('Create database backup'), array(
			'icon' => UI::icon('list'), 
			'href' => Route::get('backend')->uri(array(
				'controller' => 'backup',
				'action' => 'database'
			)),
			'class' => 'btn-primary'
		)); ?>

		<?php echo UI::button(__('Create filesystem backup'), array(
			'icon' => UI::icon('file'), 
			'href' => Route::get('backend')->uri(array(
				'controller' => 'backup',
				'action' => 'filesystem'
			)),
			'class' => 'btn-primary'
		)); ?>
	</div>

	<div id="backups-list">
		<?php echo $files; ?>
	</div>
</div>
<div class="panel">
	<div class="panel-heading" data-icon="upload">
		<span class="panel-title"><?php echo __('Upload backup file'); ?></span>
	</div>
	<div class="panel-body no-padding">
		<?php echo Form::open('api-backup.upload', array(
			'enctype' => 'multipart/form-data',
			'method' => Request::POST,
			'class' => 'dropzone',
		));
		echo Form::hidden('token', Security::token()); 
		?>
		<?php echo Form::close(); ?>
	</div>
</div>