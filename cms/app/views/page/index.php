<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<div class="page-header">
	<h1><?php echo __('Pages'); ?></h1>
</div>
					
<div id="pageMap" class="box map">
	<div class="well page-actions">
		<?php echo View::factory('page/blocks/search'); ?>
		
		<?php echo Form::button(NULL, HTML::icon('move icon-white') . ' ' . __('Reorder'), array(
			'id' => 'pageMapReorderButton', 'class' => 'btn btn-primary'
		)); ?>
		
		<?php echo Form::button(NULL, HTML::icon('random icon-white') . ' ' . __('Copy'), array(
			'id' => 'pageMapCopyButton', 'class' => 'btn btn-info'
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
					<?php echo HTML::icon('lock'); ?>
					<em title="/"><?php echo $page->title; ?></em>
					<?php else: ?>
					<?php echo HTML::icon('home'); ?>
					<a href="<?php echo URL::site('page/edit/1'); ?>" title="/"><?php echo $page->title; ?></a>
					<?php endif; ?>
					
					<?php echo HTML::anchor((URL::base()), HTML::label(__('View page')), array('class' => 'item-preview', 'target' => '_blank')); ?>
				</span>
				<span class="actions">
					<?php echo HTML::button(URL::site('page/add/'.$page->id), NULL, 'plus', 'btn btn-mini'); ?>
				</span>
			</div>
			
			<?php echo $content_children; ?>
		</li>
	</ul><!--/#pageMapItems-->
	
	<ul id="pageMapSearchItems" class="map-items"><!--x--></ul>
	
</div>