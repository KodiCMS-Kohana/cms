<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<ul class="map-level-<?php echo $level; ?>">
	<?php foreach($childrens as $child): ?>
	<li rel="<?php echo $child->id; ?>" <?php if($child->is_expanded) echo('class="item-expanded"'); ?>>
		<div class="item">
			<?php if( $child->has_children ) {
				if($child->is_expanded)
					echo HTML::icon( 'minus item-expander item-expander-expand');
				else
					echo HTML::icon( 'plus item-expander');
			}
			?>			
			<span class="title">
				<?php if( ! AuthUser::hasPermission($child->getPermissions()) ): ?>
				<?php echo HTML::icon('lock'); ?>
				<em title="/<?php echo $child->getUri(); ?>"><?php echo $child->title; ?></em>
				<?php else: ?>
				<?php echo HTML::icon('file'); ?>
				<a href="<?php echo URL::site('page/edit/'.$child->id); ?>" title="/<?php echo $child->getUri(); ?>"><?php echo $child->title; ?></a>
				<?php endif; ?>				
				<?php if( !empty($child->behavior_id) ): ?> <?php echo HTML::label(Inflector::humanize($child->behavior_id), 'default'); ?><?php endif; ?>
				<?php echo HTML::anchor(URL::base() . ($uri = $child->getUri()) . (strstr($uri, '.') === false ? URL_SUFFIX : ''), HTML::label(__('View page')), array(
					'class' => 'item-preview', 'target' => '_blankn'
				)); ?>
			</span>
			<span class="date"><?php echo Date::format($child->published_on, 'd F Y'); ?></span>
			<span class="status">
				<?php switch ($child->status_id):
					case Page::STATUS_DRAFT:    echo HTML::label(__('Draft'), 'info');       break;
					case Page::STATUS_REVIEWED: echo HTML::label(__('Reviewed'), 'info'); break;
					case Page::STATUS_HIDDEN:   echo HTML::label(__('Hidden'), 'default');     break;
					case Page::STATUS_PUBLISHED:
						if( strtotime($child->published_on) > time() )
							echo HTML::label(__('Pending'), 'success');
						else
							echo HTML::label(__('Published'), 'success');
					break;
				endswitch; ?>
			</span>
			<span class="actions">
				<?php echo HTML::button(URL::site('page/add/'.$child->id), NULL, 'plus', 'btn btn-mini'); ?>
				<?php 
				if( AuthUser::hasPermission($child->getPermissions()) )
					echo HTML::button(URL::site('page/delete/'.$child->id), NULL, 'remove', 'btn btn-mini btn-confirm');
				?>
			</span>
		</div>
		
		<?php if( $child->is_expanded ) echo($child->children_rows); ?>
	</li>
	<?php endforeach; ?>
</ul>