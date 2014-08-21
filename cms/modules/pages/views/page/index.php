<div id="page-tree" class="panel">
	<div class="panel-heading">
		<?php if ( Acl::check( 'page.add')): ?>
		<?php echo UI::button(__('Add page'), array(
			'class' => 'btn',
			'href' => Route::get('backend')->uri(array('controller' => 'page', 'action' => 'add')),
			'icon' => UI::icon('plus'),
			'data-hotkeys' => 'ctrl+a'
		)); ?>
		<?php endif; ?>

		<?php if ( Acl::check( 'page.sort')): ?>
		<?php echo UI::button(__('Reorder'), array(
			'id' => 'pageMapReorderButton', 
			'class' => 'btn btn-primary btn-sm',
			'icon' => UI::icon('arrows'),
			'data-hotkeys' => 'ctrl+s'
		)); ?>
		<?php endif; ?>

		<div class="panel-heading-controls hidden-xs hidden-sm">
			<?php echo View::factory('page/blocks/search'); ?>
		</div>
	</div>

	<table id="page-tree-header" class="table table-primary">
		<thead>
			<tr>
				<th class="col-xs-7"><?php echo __('Page'); ?></th>
				<th class="col-xs-2"><?php echo __('Date'); ?></th>
				<th class="col-xs-2"><?php echo __('Status'); ?></th>
				<th class="col-xs-1 text-right"><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
	</table>
	<ul id="page-tree-list" class="page-items list-unstyled" data-level="0">
		<li data-id="<?php echo $page->id; ?>">
			<div class="page-item">
				<div class="title col-xs-6">
					<?php if( ! ACL::check('page.edit') OR ! AuthUser::hasPermission( $page->get_permissions() ) ): ?>
					<?php echo UI::icon('lock'); ?>
					<em title="/"><?php echo $page->title; ?></em>
					<?php else: ?>
					<?php 
					echo UI::icon('home') . ' '; 
					echo HTML::anchor( $page->get_url(), $page->title );
					?>
					<?php endif; ?>

					<?php echo $page->get_public_anchor(); ?>
				</div>
				<div class="actions col-xs-offset-4 col-xs-2 text-right">
					<?php if ( Acl::check( 'page.add')): ?>
					<?php echo UI::button(NULL, array(
						'icon' => UI::icon('plus'), 
						'href' => Route::get('backend')->uri(array('controller' => 'page', 'action' => 'add')),
						'class' => 'btn btn-xs')); ?>
					<?php endif; ?>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php echo $content_children; ?>
		</li>
	</ul>
	
	<ul id="page-search-list" class="page-items no-padding-hr"></ul>
	
	<div class="clearfix"></div>
</div>