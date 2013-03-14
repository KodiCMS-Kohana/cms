<?php

// TODO: clean up code/solution
$pagetmp = Flash::get('page');
$parttmp = Flash::get('page_parts');
$tagstmp = Flash::get('page_tag');

if (!empty($pagetmp) AND !empty($parttmp) AND !empty($tagstmp))
{
	$page = $pagetmp;
	$page_parts = $parttmp;
	$tags = $tagstmp;
}
?>

<script type="text/javascript">
	var PAGE_ID = <?php echo (int) $page->id; ?>;

	<?php if($action == 'add'): ?>
	$(function() {
		$('.spoiler-toggle').click();
	})

	<?php endif; ?>
</script>


<div id="pageEdit">
	<?php echo Form::open($action == 'add' ? 'page/add/'.$parent_id : 'page/edit/'.$page->id, array(
		'id' => 'pageEditForm', 'class' => 'form-horizontal', 'method' => Request::POST
	)); ?>
		<?php echo Form::hidden('token', Security::token()); ?>
		<?php if (!empty($parent_id)): ?>
		<?php echo Form::hidden('page[parent_id]', $parent_id); ?>
		<?php endif; ?>

		<div class="container-fluid">
			<div class="row-fluid">
				<div id="pageEdit" class="box span9">
					<div class="widget widget-no-border-radius">
						<div id="pageEditMeta" class="widget-title">
							<?php echo View::factory('page/blocks/meta', array(
								'page' => $page,
								'action' => $action,
								'tags' => $tags
							)); ?>
						</div><!--/#pageEditMeta-->


						<?php echo View::factory('part/items'); ?>

						<?php Observer::notify('view_page_edit_plugins', array($page)); ?>

						<?php if($action != 'add'): ?>
						<div class="widget-content widget-no-border-radius">
							<?php if($page->title): ?>
							<?php if (isset($page->updated_on)): ?>
							<?php echo UI::label(__('Last updated by :anchor on :date', array(
								':anchor' => HTML::anchor('user/edit/' . $page->updated_by_id, $page->updated_by_name),
								':date' => Date::format($page->updated_on, 'D, j F Y')))); ?>
							<?php endif; ?>

							<?php echo HTML::anchor($page->get_url(), UI::label(__('View page')), array(
								'class' => 'item-preview', 'target' => '_blankn'
							)); ?>
							<?php endif; ?>
						</div>
						<?php endif; ?>

						<div class="form-actions widget-footer">
							<?php echo UI::actions($page_name); ?>
						</div>
					</div>
				</div><!--/#pageEdit-->

				<div class="span3">
					<?php Observer::notify('view_page_edit_sidebar_before', array($page)); ?>

					<div class="outline">
						<div id="pageEditOptions" class="widget outline_inner">
							<?php echo View::factory('page/blocks/sidebar', array(
								'page' => $page,
								'layouts' => $layouts,
								'behaviors' => $behaviors,
								'permissions' => $permissions,
								'action' => $action,
								'page_permissions' => $page_permissions
							)); ?>
						</div><!--/#pageEditOptions-->
					</div>

					<?php Observer::notify('view_page_edit_sidebar_after', array($page)); ?>
				</div><!--/#contentSidebar-->
			</div>
		</div>
	<?php echo Form::close(); ?>
</div>