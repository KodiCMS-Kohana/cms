<div class="panel">
	<div class="panel-heading">
		<?php if ( Acl::check('email.types.add')): ?>
		<?php echo UI::button(__('Add email type'), array(
			'href' => Route::get('email_controllers')->uri(array('controller' => 'types', 'action' => 'add')), 
			'icon' => UI::icon('plus'),
			'data-hotkeys' => 'ctrl+a',
			'class' => 'btn-primary'
		)); ?>
		<?php endif; ?>
	</div>
	
	<table class="table table-primary table-striped table-hover">
		<colgroup>
			<col />
			<col width="200px" />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Email type name'); ?></th>
				<th class="hidden-xs"><?php echo __('Email type code'); ?></th>
				<th class="text-right"><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($types as $type): ?>
			<tr class="item">
				<td class="name">
					<?php if (Acl::check('email.types.edit')): ?>
					<?php echo HTML::anchor(Route::get('email_controllers')->uri(array(
						'controller' => 'types',
						'action' => 'edit',
						'id' => $type->id
					)), $type->name); ?>
					<?php else: ?>
					<?php echo UI::icon('lock'); ?> <?php echo $type->name; ?>
					<?php endif; ?>
				</td>
				<td class="email_type hidden-xs">
					<?php echo UI::label($type->code); ?>
				</td>
				<td class="actions text-right">
					<?php if (Acl::check('email.types.delete')): ?>
					<?php echo UI::button(NULL, array(
						'href' => Route::get('email_controllers')->uri(array(
							'controller' => 'types',
							'action' => 'delete',
							'id' => $type->id
						)), 
						'icon' => UI::icon('times fa-inverse'),
						'class' => 'btn-xs btn-danger btn-confirm'
					)); ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php echo $pager; ?>