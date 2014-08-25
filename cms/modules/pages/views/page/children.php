<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<ul data-level="<?php echo $level; ?>" class="list-unstyled">
	<?php foreach($childrens as $child): ?>
	<?php if($child instanceof Model_Page): ?>
	<li data-id="<?php echo $child->id; ?>" <?php if($child->is_expanded):?>class="item-expanded"<?php endif; ?>>
		<div class="page-item">
			<div class="title col-xs-7">
				<?php if ($child->has_children): ?>
				<?php
					if($child->is_expanded)
					{
						echo UI::icon('minus item-expander item-expander-expand');
					}
					else
					{
						echo UI::icon('plus item-expander');
					}
				?>
				<?php endif; ?>


				<?php if (!ACL::check('page.edit') OR !Auth::has_permissions($child->get_permissions())): ?>
				<?php echo UI::icon('lock'); ?>
				<?php echo $child->title; ?>
				<?php else: ?>
				<?php echo HTML::anchor($child->get_url(), $child->title, array('data-icon' => ! empty($child->children_rows) ? 'folder-open' : 'file-o')); ?>
				<?php endif; ?>				
				<?php if( !empty($child->behavior_id) ): ?> <?php echo UI::label(__(ucfirst(Inflector::humanize( $child->behavior_id ))), 'default'); ?><?php endif; ?>
				<?php if( !empty($child->use_redirect) ): ?> <?php echo UI::label(__('Redirect: :url', array(':url' => $child->redirect_url))); ?><?php endif; ?>

				<?php echo $child->get_public_anchor(); ?>
			</div>
			<div class="date col-xs-2 text-right">
				<?php echo Date::format($child->published_on); ?>
			</div>
			<div class="status col-xs-2 text-right">
				<?php echo $child->get_status(); ?>
			</div>
			<div class="actions col-xs-1 text-right">
				<div class="btn-group">
					<?php if (Acl::check('page.add')): ?>
					<?php echo UI::button(NULL, array(
						'href' => Route::get('backend')->uri(array(
							'controller' => 'page',
							'action' => 'add',
							'id' => $child->id
						)), 
						'icon' => UI::icon('plus'), 
						'class' => 'btn btn-xs'
					)); ?>
					<?php endif; ?>
					<?php if (Acl::check( 'page.delete')): ?>
					<?php echo UI::button(NULL, array(
						'href' => Route::get('backend')->uri(array(
							'controller' => 'page',
							'action' => 'delete',
							'id' => $child->id
						)), 'icon' => UI::icon('times fa-inverse'), 
						'class' => 'btn-xs btn-confirm btn-danger'
					)); ?>
					<?php endif; ?>
				</div>
			</div>
			
			<div class="clearfix"></div>
		</div>
	</li>
	<?php if ($child->is_expanded) echo($child->children_rows); ?>
	<?php else: ?>
	<li>
		<div class="page-item">
			<div class="row">
				<div class="title col-xs-12">
					<?php echo $child; ?>
				</div>
			</div>
		</div>
	</li>
	<?php endif; ?>
	<?php endforeach; ?>
</ul>