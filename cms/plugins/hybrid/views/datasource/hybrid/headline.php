<?php if($data['total'] > 0): ?>
<table class="table table-striped">
	<colgroup>
		<?php if($datasource->has_access('document.edit')): ?>
		<col width="30px" />
		<?php endif; ?>

		<?php foreach ($fields as $key => $field): ?>
		<col <?php if (Arr::get($field, 'width') !== NULL) echo 'width="' . (int) $field['width'] . '"px'; ?>/>
		<?php endforeach; ?>
	</colgroup>
	<thead>
		<tr>
			<?php if($datasource->has_access('document.edit')): ?>
			<th></th>
			<?php endif; ?>

			<?php foreach ($fields as $key => $field): ?>
			<?php if(Arr::get($field, 'visible') === FALSE) continue; ?>
			
			<th class="<?php echo Arr::get($field, 'class'); ?>"><?php echo __(Arr::get($field, 'name')); ?></th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($data['documents'] as $id => $row): ?>
		<tr data-id="<?php echo $id; ?>" class="<?php echo !$row['published'] ? 'unpublished' : ''; ?>">
			<?php if($datasource->has_access('document.edit')): ?>
			<td class="row-checkbox"><?php echo Form::checkbox('doc[]', $id, NULL, array('class' => 'doc-checkbox')); ?></td>
			<?php endif; ?>

			<?php foreach ($fields as $key => $field): ?>
			<?php if(Arr::get($field, 'visible') === FALSE) continue; ?>

			<?php if(isset($row[$key])): ?>
				<?php if(Arr::get($field, 'type') == 'link'): ?>
					<?php if($datasource->has_access('document.view') OR $datasource->has_access('document.edit')): ?>
					<td class="row-<?php echo $key; ?> <?php echo Arr::get($field, 'class'); ?>">
						<strong>
						<?php echo HTML::anchor(Route::get('datasources')->uri(array(
							'controller' => 'document',
							'directory' => 'hybrid',
							'action' => 'view'
						)) . URL::query(array(
							'ds_id' => $datasource->id(), 'id' => $id
						)), $row[$key]); ?>
						</strong>
					</td>
					<?php else: ?>
					<td class="row-<?php echo $key; ?> <?php echo Arr::get($field, 'class'); ?>"><strong><?php echo $row[$key]; ?></strong></td>
					<?php endif; ?>

				<?php else: ?>
				<td class="row-<?php echo $key; ?> <?php echo Arr::get($field, 'class'); ?>"><?php echo $row[$key]; ?></td>
				<?php endif; ?>
			<?php else: ?>
				<td class="row-<?php echo $key; ?> <?php echo Arr::get($field, 'class'); ?>"></td>
			<?php endif; ?>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo __('Total doucments: :num', array(':num' => $data['total'])); ?>
<hr />
<?php echo $pagination; ?>
<?php else: ?>
<h2><?php echo __('Section is empty'); ?></h2>
<?php endif; ?>
