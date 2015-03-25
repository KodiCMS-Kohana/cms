 <div class="panel">
	 <?php if (is_writable(SNIPPETS_SYSPATH)): ?>
	<div class="panel-heading">
		<?php if (ACL::check('snippet.add')): ?>
		<?php echo UI::button(__('Add snippet'), array(
			'href' => Route::get('backend')->uri(array('controller' => 'snippet', 'action' => 'add')), 
			'icon' => UI::icon('plus'),
			'data-hotkeys' => 'ctrl+a',
			'class' => 'btn-primary'
		)); ?>
		<?php endif; ?>
	</div>
	<?php else: ?>
	<div class="alert alert-danger alert-dark no-margin-b">
		<?php echo __('Folder :folder is not writable', array(
			':folder' => SNIPPETS_SYSPATH
		)); ?>
	</div>
	<?php endif; ?>

	<table class="table-primary table-light table table-striped table-hover">
		<colgroup>
			<col />
			<col width="150px" />
			<col width="100px" />
			<col width="200px" />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Snippet name'); ?></th>
				<th class="hidden-xs"><?php echo __('Modified'); ?></th>
				<th><?php echo __('Size'); ?></th>
				<th class="hidden-xs"><?php echo __('Direction'); ?></th>
				<th class="text-right"><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($snippets as $snippet): ?>
			<tr>
				<th class="name">
					<?php echo UI::icon('file-code-o'); ?>
					<?php if ($snippet->is_read_only()): ?>
					<span class="label label-warning"><?php echo __('Read only'); ?></span>
					<?php endif; ?>

					<?php if (ACL::check('snippet.edit') OR ACL::check('snippet.view')): ?>
					<?php echo HTML::anchor(Route::get('backend')->uri(array(
						'controller' => 'snippet', 
						'action' => 'edit', 
						'id' => $snippet->name
					)), $snippet->name, array(
						'class' => $snippet->is_read_only() ? 'popup fancybox.iframe' : ''
					)); ?>
					<?php else: ?>
					<?php echo UI::icon('lock'); ?> <?php echo $snippet->name; ?>
					<?php endif; ?>
				</th>
				<td class="modified hidden-xs">
					<?php echo Date::format($snippet->modified()); ?>
				</td>
				<td class="size">
					<?php echo Text::bytes($snippet->size()); ?>
				</td>
				<td class="direction hidden-xs">
					<?php echo UI::label($snippet->get_relative_path()); ?>
				</td>
				<td class="actions text-right">
					<?php if (ACL::check('snippet.delete') AND !$snippet->is_read_only()): ?>
					<?php echo UI::button(NULL, array(
						'href' => Route::get('backend')->uri(array('controller' => 'snippet', 'action' => 'delete', 'id' => $snippet->name)), 
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