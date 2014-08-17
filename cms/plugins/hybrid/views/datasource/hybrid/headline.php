<table class="table-primary table table-striped">
	<colgroup>
		<?php if(Acl::check('hybrid'.$ds_id.'.document.edit')): ?>
		<col width="30px" />
		<?php endif; ?>

		<?php foreach ($fields as $key => $field): ?>
		<col <?php if(Arr::get($field, 'width') !== NULL) echo 'width="'.$field['width'].'"px'; ?>/>
		<?php endforeach; ?>
	</colgroup>
	<thead>
		<tr>
			<?php if(Acl::check('hybrid'.$ds_id.'.document.edit')): ?>
			<th class="row-checkbox"><?php echo Form::checkbox('check_all', NULL, NULL, array(
				'data-target' => '.doc-checkbox',
				'data-hotkeys' => 'ctrl+shift+a'
			)); ?></th>
			<?php endif; ?>

			<?php foreach ($fields as $key => $field): ?>
			<th><?php echo __(Arr::get($field, 'name')); ?></th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($data['documents'] as $id => $row): ?>
		<tr data-id="<?php echo $id; ?>" class="<?php echo !$row['published'] ? 'unpublished' : ''; ?>">
			<?php if(Acl::check('hybrid'.$ds_id.'.document.edit')): ?>
			<td class="row-checkbox"><?php echo Form::checkbox('doc[]', $id, NULL, array('class' => 'doc-checkbox')); ?></td>
			<?php endif; ?>

			<?php foreach ($fields as $key => $field): ?>
			<?php if(isset($row[$key])): ?>
				<?php if(Arr::get($field, 'type') == 'link'): ?>
					<?php if(Acl::check('hybrid'.$ds_id.'.document.view') OR Acl::check('hybrid'.$ds_id.'.document.edit')): ?>
					<td class="row-<?php echo $key; ?>">
						<strong>
						<?php echo HTML::anchor(Route::get('datasources')->uri(array(
							'controller' => 'document',
							'directory' => 'hybrid',
							'action' => 'view'
						)) . URL::query(array(
							'ds_id' => $ds_id, 'id' => $id
						)), $row[$key]); ?>
						</strong>
					</td>
					<?php else: ?>
					<td class="row-<?php echo $key; ?>"><strong><?php echo $row[$key]; ?></strong></td>
					<?php endif; ?>

				<?php else: ?>
				<td class="row-<?php echo $key; ?>"><?php echo $row[$key]; ?></td>
				<?php endif; ?>
			<?php else: ?>
				<td class="row-<?php echo $key; ?>"></td>
			<?php endif; ?>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<div class="panel-body">
	<?php echo $pagination; ?>
</div>
<div class="panel-footer">
	<?php echo __('Total doucments: :num', array(':num' => $data['total'])); ?>
</div>

