<div class="panel">
	<div class="panel-heading">
		<?php if (ACL::check('widgets.add')): ?>
		<?php echo UI::button(__('Add widget'), array(
			'href' => Route::get('backend')->uri(array(
				'controller' => 'widgets', 
				'action' => 'add')),
			'icon' => UI::icon('plus'),
			'data-hotkeys' => 'ctrl+a',
			'class' => 'btn-primary'
		)); ?>
		<?php endif; ?>
	</div>
	<?php echo $sidebar; ?>
	<table class="table table-primary table-striped table-hover">
		<colgroup>
			<col width="250px" />
			<col width="150px" />
			<col />
			<col width="150px" />
			<col width="150px" />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Widget name'); ?></th>
				<th><?php echo __('Type'); ?></th>
				<th class="hidden-xs"><?php echo __('Description'); ?></th>
				<th class="hidden-xs"><?php echo __('Widget template'); ?></th>
				<th class="hidden-xs"><?php echo __('Cache lifetime'); ?></th>
				<th class="text-right"><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($widgets as $widget): ?>
			<tr data-id="<?php echo $widget->id; ?>">
				<th class="name">
					<?php echo UI::icon('cube'); ?> 
					<?php if (ACL::check('widgets.edit')): ?>
					<?php echo HTML::anchor(Route::get('backend')->uri(array(
						'controller' => 'widgets', 
						'action' => 'edit',
						'id' => $widget->id)), $widget->name); ?>
					<?php else: ?>
					<?php echo UI::icon('lock'); ?> <?php echo $widget->name; ?>
					<?php endif; ?>
					
					<?php if ($widget->code()->is_handler()): ?>
					<?php echo UI::label(__('Handler'), 'warning'); ?>
					<?php endif; ?>
				</th>
				<td class="type">
					<?php echo UI::label($widget->type()); ?>
				</td>
				<td class="description hidden-xs">
					<span class="text-muted"><?php echo $widget->description; ?></span>
					
					<?php if ($widget->code()->is_handler()): ?>
					<span class="text-muted text-xs"><?php echo __('To use handler send your data to URL :href or use route :route', array(
						':href' => '<code>' . URL::site($widget->code()->link(), TRUE) . '</code>',
						':route' => '<code>Route::get(\'handler\')->uri(array(\'id\' => ' .$widget->id. '));</code>'
					)); ?></span>
					<?php endif; ?>
				</td>
				<td class="template hidden-xs">
				<?php if ($widget->code()->use_template()): ?>
					<span class="editable-template label label-info" data-value="<?php echo empty($widget->template) ? 0 : $widget->template; ?>"><?php echo $widget->template; ?></span>
				<?php endif; ?>
				</td>
				<td class="cache hidden-xs">
				<?php if ($widget->code()->use_caching()): ?>
					<?php if ($widget->code()->caching === FALSE): ?>
					<?php echo UI::label('0', 'warning'); ?>
					<?php else: ?>
					<?php echo UI::label($widget->code()->cache_lifetime, 'success'); ?>
					<?php endif; ?>
				<?php endif; ?>
				</td>
				<td class="actions text-right">
					<?php if (ACL::check('widgets.location') AND !$widget->code()->is_handler()): ?>
					<?php echo UI::button(NULL, array(
						'href' => Route::get('backend')->uri(array(
							'controller' => 'widgets', 
							'action' => 'location',
							'id' => $widget->id)), 
						'icon' => UI::icon('sitemap'),
						'class' => 'btn-xs btn-primary popup fancybox.iframe'
					)); ?>
					<?php endif; ?>
					<?php if (ACL::check('widgets.delete')): ?>
					<?php echo UI::button(NULL, array(
						'href' => Route::get('backend')->uri(array(
							'controller' => 'widgets', 
							'action' => 'delete',
							'id' => $widget->id)), 
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