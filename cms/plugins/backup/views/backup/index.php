<div class="page-header">
	<h1><?php echo __( 'Backup' ); ?></h1>
</div>

<div class="well page-actions">
	<?php echo UI::button(__('Create database backup'), array(
		'icon' => UI::icon('list'), 'href' => 'backup/database'
	)); ?>
	
	<?php echo UI::button(__('Create filesystem backup'), array(
		'icon' => UI::icon('file'), 'href' => 'backup/filesystem'
	)); ?>
</div>

<div id="backup-container" class="map">
	
	<div id="backups-list">
		<table class="table table-striped table-hover">
			<colgroup>
				<col width="150px" />
				<col />
				<col width="90px" />
				<col width="90px" />
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
						'class' => 'btn btn-mini', 'href' => 'backup/restore/'.$filename, 
						'icon' => UI::icon( 'off' )
					));?> 
					<?php echo UI::button(NULL, array(
						'class' => 'btn btn-mini btn-danger', 'href' => 'backup/delete/'.$filename, 
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
<br />
<div class="well form-inline">
	<h4><?php echo __('Upload backup file'); ?></h4>
	<hr />
	<?php 
		echo Form::open('backup/upload', array(
			'enctype' => 'multipart/form-data',
			'method' => Request::POST
		));
		echo Form::hidden('token', Security::token());
		echo Form::file('file', array('id' => 'backup-upload'));
		echo UI::button(__('Upload'), array('class' => 'btn', 'name' => 'upload'));
		echo Form::close(); 
	?>
</div>