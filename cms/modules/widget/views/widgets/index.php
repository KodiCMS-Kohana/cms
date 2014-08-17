<div class="row">
	<div class="col-md-9">
		<div class="panel">
			<div class="panel-heading">
				<?php if( ACL::check('widgets.add')): ?>
				<?php echo UI::button(__('Add widget'), array(
					'href' => Route::get('backend')->uri(array(
						'controller' => 'widgets', 
						'action' => 'add')),
					'icon' => UI::icon('plus'),
					'data-hotkeys' => 'ctrl+a'
				)); ?>
				<?php endif; ?>
			</div>

			<table class="table table-primary table-striped table-hover">
				<colgroup>
					<col width="250px" />
					<col width="150px" />
					<col />
					<col width="150px" />
					<col width="100px" />
				</colgroup>
				<thead>
					<tr>
						<th><?php echo __('Widget name'); ?></th>
						<th><?php echo __('Type'); ?></th>
						<th><?php echo __('Description'); ?></th>
						<th><?php echo __('Cache time'); ?></th>
						<th><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($widgets as $widget): ?>
					<tr>
						<th class="name">
							<?php echo UI::icon('cube'); ?> 
							<?php if( ACL::check('widgets.edit')): ?>
							<?php echo HTML::anchor(Route::get('backend')->uri(array(
								'controller' => 'widgets', 
								'action' => 'edit',
								'id' => $widget->id)), $widget->name); ?>
							<?php else: ?>
							<?php echo UI::icon('lock'); ?> <?php echo $widget->name; ?>
							<?php endif; ?>
						</th>
						<td class="type">
							<?php echo UI::label($widget->type()); ?>
						</td>
						<td class="description">
							<span class="muted"><?php echo $widget->description; ?></span>
						</td>
						<td class="cache">
							<?php if($widget->code()->caching === FALSE): ?>
							<?php echo UI::label('0', 'warning'); ?>
							<?php else: ?>
							<?php echo UI::label($widget->code()->cache_lifetime, 'success'); ?>
							<?php endif; ?>
						</td>
						<td class="actions text-right">
							<?php if( ACL::check('widgets.location') ): ?>
							<?php echo UI::button(NULL, array(
								'href' => Route::get('backend')->uri(array(
									'controller' => 'widgets', 
									'action' => 'location',
									'id' => $widget->id)), 
								'icon' => UI::icon('sitemap'),
								'class' => 'btn btn-xs btn-primary'
							)); ?>
							<?php endif; ?>
							<?php if( ACL::check('widgets.delete')): ?>
							<?php echo UI::button(NULL, array(
								'href' => Route::get('backend')->uri(array(
									'controller' => 'widgets', 
									'action' => 'delete',
									'id' => $widget->id)), 
								'icon' => UI::icon('times fa-inverse'),
								'class' => 'btn btn-xs btn-danger btn-confirm'
							)); ?>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php echo $pager; ?>
	</div>
	<div class="col-md-3">
		<?php echo $sidebar; ?>
	</div>
</div>