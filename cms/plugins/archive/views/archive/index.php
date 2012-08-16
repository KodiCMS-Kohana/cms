<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<div class="page-header">
	<h1><?php echo $page->title ?></h1>
</div>

<div class="map">
	<div class="well page-actions">
		<?php echo HTML::button(URL::site('page/add/'.$page->id), __('Add page'), 'plus'); ?>
	</div>
	
	<table class="table_list" id="SnippetList">
		<colgroup>
			<col />
			<col width="150px" />
			<col width="150px" />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Page'); ?></th>
				<th><?php echo __('Status'); ?></th>
				<th><?php echo __('Date'); ?></th>
				<th><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($items as $item): ?>
			<tr rel="<?php echo $item->id; ?>">
				<th class="title">
					<?php if( ! AuthUser::hasPermission($item->getPermissions()) ): ?>
					<img src="images/page-text-locked.png" title="<?php echo('You do not have permission to access the requested page!'); ?>" />
					<em title="/<?php echo $item->getUri(); ?>"><?php echo $item->title; ?></em>
					<?php else: ?>
					<?php echo HTML::icon('file'); ?>
					<a href="<?php echo URL::site('page/edit/'.$item->id); ?>" title="/<?php echo $item->getUri(); ?>"><?php echo $item->title; ?></a>
					<?php endif; ?>
				</th>
				<td class="date">
					<?php echo Date::format($item->published_on, 'd F Y'); ?>
				</td>
				<td class="status">
					<?php switch ($item->status_id):
						case Page::STATUS_DRAFT:    echo HTML::label(__('Draft'), 'info');       break;
						case Page::STATUS_REVIEWED: echo HTML::label(__('Reviewed'), 'info'); break;
						case Page::STATUS_HIDDEN:   echo HTML::label(__('Hidden'), 'default');     break;
						case Page::STATUS_PUBLISHED:
							if( strtotime($item->published_on) > time() )
								echo HTML::label(__('Pending'), 'success');
							else
								echo HTML::label(__('Published'), 'success');
						break;
					endswitch; ?>
				</td>
				<td class="actions">
					<?php echo HTML::button(URL::site('page/add/'.$item->id), NULL, 'plus', 'btn btn-mini'); ?>
					<?php 
					if( AuthUser::hasPermission($item->getPermissions()) )
						echo HTML::button(URL::site('page/delete/'.$item->id), NULL, 'remove', 'btn btn-mini btn-confirm');
					?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php echo $pager; ?>