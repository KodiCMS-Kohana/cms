<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<div class="page-header">
	<h1><?php echo __('Pages'); ?></h1>
</div>
					
<div id="pageMap" class="box map">
	<div class="well page-actions">
		<?php echo View::factory('page/blocks/search'); ?>
		
		<?php echo UI::button(__('Reorder'), array(
			'id' => 'pageMapReorderButton', 'class' => 'btn btn-primary',
			'icon' => UI::icon('move icon-white')
		)); ?>
		
		<?php echo UI::button(__('Copy'), array(
			'id' => 'pageMapCopyButton', 'class' => 'btn btn-info',
			'icon' => UI::icon('random icon-white')
		)); ?>
	</div>
	
	<div id="pageMapHeader" class="map-header">
		<span class="title"><?php echo __('Page'); ?></span>
		<span class="status"><?php echo __('Status'); ?></span>
		<span class="date"><?php echo __('Date'); ?></span>
		<span class="actions"><?php echo __('Actions'); ?></span>
	</div>
	
	<ul id="pageMapItems" class="map-items">
		<li rel="<?php echo $page->id; ?>" class="map-level-0">
			<div class="item">
				<span class="title">
					<?php if( ! AuthUser::hasPermission($page->getPermissions()) ): ?>
					<?php echo UI::icon('lock'); ?>
					<em title="/"><?php echo $page->title; ?></em>
					<?php else: ?>
					<?php 
					echo UI::icon('home'); 
					echo HTML::anchor( URL::site('page/edit/1'), $page->title );
					?>
					<?php endif; ?>
					
					<?php echo HTML::anchor((URL::base(TRUE)), UI::label(__('View page')), array('class' => 'item-preview', 'target' => '_blank')); ?>
				</span>
				<span class="actions">
					<?php echo UI::button(NULL, array(
						'icon' => UI::icon('plus'), 'href' => 'page/add/'.$page->id,
						'class' => 'btn btn-mini')); ?>
				</span>
			</div>
			
			<?php echo $content_children; ?>
		</li>
	</ul><!--/#pageMapItems-->
	
	<ul id="pageMapSearchItems" class="map-items"><!--x--></ul>
	
</div>