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
		
		<span class="clearfix"></span>
	</div>
	
	
	<table id="pageMapHeader" class="table">
		<colgroup>
			<col />
			<col width="14%" />
			<col width="14%" />
			<col width="7%" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Page'); ?></th>
				<th class="align-right"><?php echo __('Date'); ?></th>
				<th class="align-right"><?php echo __('Status'); ?></th>
				<th class="align-right"><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
	</table>
	
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