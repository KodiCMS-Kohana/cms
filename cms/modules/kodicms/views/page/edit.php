<script type="text/javascript">
	var PAGE_ID = <?php echo (int) $page->id; ?>;

	<?php if($action == 'add'): ?>
	$(function() {
		$('.spoiler-toggle').click();
	})

	<?php endif; ?>
</script>

<div id="pageEdit">
	<?php echo Form::open(Route::url('backend', array('controller' => 'page', 'action' => $action, 'id' => $action == 'add' ? $parent_id : $page->id)), array(
		'id' => 'pageEditForm', 'class' => Bootstrap_Form::HORIZONTAL, 'method' => Request::POST
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
						</div>
						<?php echo View::factory('part/items'); ?>

						<?php Observer::notify('view_page_edit_plugins', $page); ?>

						<?php if($action != 'add'): ?>
						<div class="widget-content widget-no-border-radius">
							<?php if($page->title): ?>
							<?php if (isset($page->updated_on)): ?>
							<?php echo UI::label(__('Last updated by :anchor on :date', array(
								':anchor' => HTML::anchor(Route::url('backend', array(
										'controller' => 'users',
										'action' => 'edit', 
										'id' => $page->updated_by_id
									)), $page->updated_by_name),
								':date' => Date::format($page->updated_on, 'D, j F Y'))), 'important'); ?>
							<?php endif; ?>

							<?php echo HTML::anchor($page->get_frontend_url(), UI::label(UI::icon('globe icon-white') . ' ' . __('View page')), array(
								'class' => 'item-preview', 'target' => '_blank'
							)); ?>
							<?php endif; ?>
						</div>
						<?php endif; ?>

						<div class="form-actions widget-footer">
							<?php echo UI::actions($page_name); ?>
						</div>
					</div>
				</div>

				<div class="span3">
					<?php Observer::notify('view_page_edit_sidebar_before', $page); ?>

					<div id="pageEditOptions" class="widget">
						<?php echo View::factory('page/blocks/sidebar', array(
							'pages' => $pages,
							'page' => $page,
							'layouts' => $layouts,
							'behaviors' => $behaviors,
							'permissions' => $permissions,
							'action' => $action,
							'page_permissions' => $page_permissions
						)); ?>
					</div>

					<?php Observer::notify('view_page_edit_sidebar_after', $page); ?>
				</div>
			</div>
		</div>
	<?php echo Form::close(); ?>
</div>