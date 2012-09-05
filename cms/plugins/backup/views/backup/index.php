<div class="page-header">
	<h1><?php echo __( 'Backup' ); ?></h1>
</div>

<div class="well page-actions">
	<?php echo HTML::button(URL::site('backup/create'), __('Create backup'), 'plus'); ?>
</div>

<div id="backup-container" class="map">
	
	<div id="backups-list">
		<table class="table_list">
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
					<?php echo HTML::anchor(URL::site('plugin/backup/view/'.$filename), $filename); ?>
				</th>
				<td><?php echo $data['size']; ?></td>
				<td>
					<?php echo HTML::button(URL::site('plugin/backup/restore/'.$filename), NULL, 'ok', 'btn btn-mini'); ?>
					<?php echo HTML::button(URL::site('plugin/backup/delete/'.$filename), NULL, 'trash', 'btn btn-mini btn-danger'); ?>
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
	<form enctype='multipart/form-data' method='post' action='<?php echo URL::site('backup/upload'); ?>'> 
		<input name="file" type="file" id="backup-upload" multiple  />
		<button class="btn"><?php echo __('Upload'); ?></button>
	</form>
</div>