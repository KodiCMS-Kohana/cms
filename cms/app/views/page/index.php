<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<div class="page-header">
	<h1><?php echo __('Pages'); ?></h1>
</div>
					
<div id="pageMap">

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
	
<div class="map-header">
	<div class="row-fluid">
		<div class="span7"><?php echo __('Page'); ?></div>
		<div class="span2"><?php echo __('Date'); ?></div>
		<div class="span2"><?php echo __('Status'); ?></div>
		<div class="span1"><?php echo __('Actions'); ?></div>
	</div>
</div>
	
	<ul id="pageMapItems" class="map-items unstyled" data-level="0">
		<li data-id="<?php echo $page->id; ?>">
			<div class="item">
				<div class="row-fluid">
					<div class="title span7">
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
					</div>
					<div class="actions offset4 span1">
						<?php echo UI::button(NULL, array(
							'icon' => UI::icon('plus'), 'href' => 'page/add/'.$page->id,
							'class' => 'btn btn-mini')); ?>
					</div>
				</div>
			</div>
			
			<?php echo $content_children; ?>
		</li>
	</ul><!--/#pageMapItems-->
	
	<ul id="pageMapSearchItems" class="map-items"><!--x--></ul>
	
</div>