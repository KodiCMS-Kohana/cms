<div class="widget widget-nopad">
	<div class="widget-header">
		<?php echo UI::button(__('Create database backup'), array(
			'icon' => UI::icon('list'), 'href' => 'backup/database'
		)); ?>

		<?php echo UI::button(__('Create filesystem backup'), array(
			'icon' => UI::icon('file'), 'href' => 'backup/filesystem'
		)); ?>
	</div>

	<div id="backup-container" class="widget-content">

		<div id="backups-list">
			<table class="table table-striped table-hover">
				<colgroup>
					<col width="150px" />
					<col />
					<col width="90px" />
					<col width="110px" />
				</colgroup>
				<thead>
					<tr>
						<th><?php echo __('Created'); ?></th>
						<th><?php echo __('File name'); ?></th>
						<th><?php echo __('File size'); ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($files as $filename => $data): ?>
				<tr>
					<td><?php echo $data['date']; ?></td>
					<th>
						<?php echo HTML::anchor('backup/view/'.$filename, $filename); ?>
					</th>
					<td><?php echo $data['size']; ?></td>
					<td>
						<?php echo UI::button(NULL, array(
							'class' => 'btn btn-mini', 
							'href' => 'download/'.Download::secure_path( BACKUP_PLUGIN_FOLDER . $filename), 
							'icon' => UI::icon( 'download' )
						));?>
						<?php echo UI::button(NULL, array(
							'class' => 'btn btn-mini btn-success btn-confirm', 'href' => 'backup/restore/'.$filename, 
							'icon' => UI::icon( 'off icon-white' )
						));?> 
						<?php echo UI::button(NULL, array(
							'class' => 'btn btn-mini btn-danger btn-confirm', 'href' => 'backup/delete/'.$filename, 
							'icon' => UI::icon( 'trash icon-white' )
						));
						?>
					</td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="widget">
	<div class="widget-header">
		<?php echo UI::icon('upload'); ?><h3><?php echo __('Upload backup file'); ?></h3>
	</div>
	<div class="widget-content">
		<?php 
		echo Form::open('backup/upload', array(
			'enctype' => 'multipart/form-data',
			'method' => Request::POST
		));
		echo Form::hidden('token', Security::token()); 
		?>
		<div class="input-append">
			<?php 
			echo Form::file('file', array('id' => 'backup-upload'));
			echo UI::button(__('Upload'), array('class' => 'btn', 'name' => 'upload'));
			?>
		</div>
		<?php echo Form::close(); ?>
	</div>
</div>