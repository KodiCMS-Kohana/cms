<h1><?php echo $page->title ?></h1>

<div id="pageMap" class="box map">
	
	<div id="pageMapActions" class="box-actions">
		<button rel="<?php echo get_url('page/add/'.$page->id); ?>" id="pageMapAddButton" class="button-image"><img src="images/add.png" /> <?php echo __('Add page'); ?></button>
	</div>

	<div id="pageMapHeader" class="map-header">
		<span class="title"><?php echo __('Page'); ?> (<?php echo __('published :count', array(':count' => $total)); ?>)</span>
		<span class="status"><?php echo __('Status'); ?></span>
		<span class="date"><?php echo __('Date'); ?></span>
		<span class="actions"><?php echo __('Actions'); ?></span>
	</div>
	
	<ul id="pageMapItems" class="map-items">
		<?php foreach($items as $item): ?>
		<li rel="<?php echo $item->id; ?>">
			<div class="item">
				<span class="title">
					<?php if( ! AuthUser::hasPermission($item->getPermissions()) ): ?>
					<img src="images/page-text-locked.png" title="<?php echo('You do not have permission to access the requested page!'); ?>" />
					<em title="/<?php echo $item->getUri(); ?>"><?php echo $item->title; ?></em>
					<?php else: ?>

					<img src="images/page-text.png" />
					<a href="<?php echo get_url('page/edit/'.$item->id); ?>" title="/<?php echo $item->getUri(); ?>"><?php echo $item->title; ?></a>
					<?php endif; ?>	

					<a class="item-preview" href="<?php echo(CMS_URL . ($uri = $item->getUri()) . (strstr($uri, '.') === false ? URL_SUFFIX : '')); ?>" target="_blank" title="<?php echo __('View page'); ?>"><img src="images/newwindow.png" /></a>

				</span>
				<span class="date"><?php echo date('Y-m-d', strtotime($item->published_on)); ?></span>
				<span class="status">
					<?php switch ($item->status_id):
						case Page::STATUS_DRAFT:    echo('<em class="item-status-draft">'.__('Draft').'</em>');       break;
						case Page::STATUS_REVIEWED: echo('<em class="item-status-reviewed">'.__('Reviewed').'</em>'); break;
						case Page::STATUS_HIDDEN:   echo('<em class="item-status-hidden">'.__('Hidden').'</em>');     break;
						case Page::STATUS_PUBLISHED:
							if( strtotime($item->published_on) > time() )
								echo('<em class="item-status-pending">'.__('Pending').'</em>');
							else
								echo('<em class="item-status-published">'.__('Published').'</em>');
						break;
					endswitch; ?>
				</span>
				<span class="actions">
					<?php if( ! AuthUser::hasPermission($item->getPermissions()) ): ?>
					<button disabled><img src="images/remove.png" /></button>
					<?php else: ?>
					<button rel="<?php echo get_url('page/delete/'.$item->id); ?>" class="item-remove-button" title="<?php echo __('Remove'); ?>"><img src="images/remove.png" /></button>
					<?php endif; ?>
				</span>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
</div>

<?php echo $pager; ?>