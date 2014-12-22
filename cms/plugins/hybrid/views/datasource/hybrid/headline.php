<?php if($data['total'] > 0): ?>
<table class="table table-striped">
	<colgroup>
		<?php if($datasource->has_access('document.edit')): ?>
		<col width="30px" />
		<?php endif; ?>

		<?php foreach ($fields as $key => $field): ?>
		<?php if(Arr::get($field, 'visible') === FALSE) continue; ?>
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
		<?php foreach ($data['documents'] as $id => $document): ?>
		<tr data-id="<?php echo $id; ?>" class="<?php if($document->is_published() === FALSE) echo 'unpublished'; ?>">
			<?php if($datasource->has_access('document.edit')): ?>
			<td class="row-checkbox">
				<?php if($document->has_access_edit()): ?>
				<?php echo Form::checkbox('doc[]', $id, NULL, array('class' => 'doc-checkbox')); ?>
				<?php endif; ?>
			</td>
			<?php endif; ?>

			<?php foreach ($fields as $key => $field): ?>
			<?php if(Arr::get($field, 'visible') === FALSE) continue; ?>
			<?php if(isset($document->$key)): ?>
				<?php if(Arr::get($field, 'type') == 'link'): ?>
					<?php if($document->has_access_view()): ?>
					<td class="row-<?php echo $key; ?> <?php echo Arr::get($field, 'class'); ?>">
						<strong>
						<?php echo HTML::anchor($document->edit_link(), $document->$key); ?>
						</strong>
					</td>
					<?php else: ?>
					<td class="row-<?php echo $key; ?> <?php echo Arr::get($field, 'class'); ?>"><strong><?php echo $document->$key; ?></strong></td>
					<?php endif; ?>
				<?php else: ?>
				<td class="row-<?php echo $key; ?> <?php echo Arr::get($field, 'class'); ?>"><?php echo $document->$key; ?></td>
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
