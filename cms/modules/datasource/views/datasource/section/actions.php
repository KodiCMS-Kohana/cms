<div class="btn-toolbar" role="toolbar">
	<?php if ($datasource->has_access('document.edit')): ?>
	<div class="btn-group checkbox-control">		
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-check-square-o"></i>&nbsp;<i class="fa fa-caret-down"></i>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li><a href="#" class="action" data-action="check_all"><?php echo __('Check all'); ?></a></li>
				<li class="divider"></li>
				<li><a href="#" class="action" data-action="uncheck_all"><?php echo __('Uncheck all'); ?></a></li>
			</ul>
		</div>
	</div>
	<?php endif; ?>
	
	<div class="btn-group doc-actions">
		<?php if ($datasource->has_access('document.edit')): ?>
		<button type="button" data-action="publish" class="btn btn-default action disabled" data-icon="eye" title="<?php echo __('Publish'); ?>"></button>
		<button type="button" data-action="unpublish" class="btn btn-default action disabled" data-icon="eye-slash" title="<?php echo __('Unpublish'); ?>"></button>
		<?php endif; ?>
		
		<?php if ($datasource->has_access('document.remove')): ?>
		<button type="button" data-action="remove" class="btn btn-warning action disabled" data-icon="trash-o" title="<?php echo __('Remove'); ?>"></button>
		<?php endif; ?>
	</div>

	<div class="btn-group">
		<?php if ($datasource->has_access('document.create')): ?>
		<?php echo UI::button(UI::hidden(__('Create document')), array(
			'href' => Route::get('datasources')->uri(array(
				'controller' => 'document',
				'directory' => $datasource->type(),
				'action' => 'create'
			)) . URL::query(array('ds_id' => $datasource->id())),
			'icon' => UI::icon('plus'),
			'data-hotkeys' => 'ctrl+a',
			'class' => 'btn-primary'
		)); ?>
		<?php endif; ?>
	</div>
	
	<?php Observer::notify('datasource.headline.actions', $datasource); ?>
</div>