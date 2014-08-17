<div class="panel">
	<div class="panel-heading">
		<?php if( ACL::check( 'layout.add')): ?>
		<?php echo UI::button(__('Add layout'), array(
			'icon' => UI::icon( 'plus' ), 
			'href' => Route::get('backend')->uri(array('controller' => 'layout', 'action' => 'add')),
			'data-hotkeys' => 'ctrl+a'
		)); ?>
		<?php endif; ?>

		<?php if( ACL::check( 'layout.rebuild')): ?>
		<?php echo UI::button(__('Rebuild blocks'), array(
			'icon' => UI::icon( 'refresh' ),
			'class' => 'btn btn-primary btn-xs btn-api',
			'data-url' => 'layout.rebuild',
			'data-method' => Request::POST
		)); ?>
		<?php endif; ?>
	</div>

	<table class="table-primary table table-striped table-hover" id="LayoutList">
		<colgroup>
			<col />
			<col width="150px" />
			<col width="100px" />
			<col width="100px" />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Layout name'); ?></th>
				<th><?php echo __('Modified'); ?></th>
				<th><?php echo __('Size'); ?></th>
				<th><?php echo __('Direction'); ?></th>
				<th><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($layouts as $layout): ?>
			<tr id="layout_<?php echo $layout->name; ?>">
				<th class="name">
					<?php echo UI::icon( 'desktop' ); ?>

					<?php if( ! $layout->is_writable()): ?>
					<span class="label label-warning"><?php echo __('Read only'); ?></span>
					<?php endif; ?>

					<?php if( ACL::check( 'layout.edit') OR ACL::check( 'layout.view')): ?>
					<?php echo HTML::anchor(Route::get('backend')->uri(array(
						'controller' => 'layout', 
						'action' => 'edit', 
						'id' => $layout->name
					)), $layout->name, array(
						'class' => ! $layout->is_writable() ? 'popup fancybox.iframe' : ''
					)); ?>
					<?php else: ?>
					<?php echo UI::icon('lock'); ?> <?php echo $layout->name; ?>
					<?php endif; ?>
					<?php if(count($layout->blocks()) > 0): ?>
					<span class="muted">
						<?php echo __('Layout blocks'); ?>: <span class="layout-block-list"><?php echo implode(', ', $layout->blocks()); ?></span>
					</span>
					<?php endif; ?>
				</th>
				<td class="modified">
					<?php echo Date::format($layout->modified()); ?>
				</td>
				<td class="size">
					<?php echo Text::bytes( $layout->size()); ?>
				</td>
				<td class="direction">
					<?php echo UI::label($layout->get_relative_path()); ?>
				</td>
				<td class="actions">
					<?php if (ACL::check('layout.delete')): ?>
					<?php echo UI::button(NULL, array(
						'icon' => UI::icon('times fa-inverse'),
						'href' => Route::get('backend')->uri(array('controller' => 'layout', 'action' => 'delete', 'id' => $layout->name)),
						'class' => 'btn btn-danger btn-xs btn-confirm'
					)); ?>
					<?php endif; ?>
				</td>
			</tr>

			<?php endforeach; ?>
		</tbody>
	</table>
</div>