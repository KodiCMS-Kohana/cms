<script type="text/javascript">
	var PAGE_ID = <?php echo (int) $page->id; ?>;
	var PAGE_OBJECT = <?php echo json_encode($page->as_array()); ?>;
	<?php if($action == 'add'): ?>
	$(function() {
		$('.spoiler-toggle').click();
	})
	<?php endif; ?>
</script>

<div id="pageEdit">
	<?php echo Form::open(Route::get('backend')->uri(array('controller' => 'page', 'action' => $action, 'id' => $action == 'add' ? $parent_id : $page->id)), array(
		'method' => Request::POST
	)); ?>
		<?php echo Form::hidden('token', Security::token()); ?>

		<?php if (!empty($parent_id)): ?>
		<?php echo Form::hidden('page[parent_id]', $parent_id); ?>
		<?php endif; ?>
	
		<ul class="nav nav-tabs">
			<li class="active" id="page-content-panel-li">
				<a href="#page-content-panel" data-toggle="tab">Content</a>
			</li>
			<li id="page-meta-panel-li">
				<a href="#page-meta-panel" data-toggle="tab" data-icon="send-o"><?php echo __('Metadata'); ?></a>
			</li>
			<li id="page-options-panel-li">
				<a href="#page-options-panel" data-toggle="tab" data-icon="cogs"><?php echo __('Page options'); ?></a>
			</li>
		</ul>
		<div class="panel form-horizontal">
			<div class="panel-heading">
				<div class="form-group form-group-lg">
					<?php echo $page->label('title', array('class' => 'control-label col-md-2')); ?>
					<div class="col-md-10">
						<?php echo $page->field('title', array(
							'class' => 'form-control slug-generator',
							'prefix' => 'page'
						)); ?>
					</div>
				</div>
				
				<hr class="panel-wide" />
				<?php if ($page->id != 1): ?>
				<div class="form-group">
					<?php echo $page->label('slug', array('class' => 'control-label col-md-2')); ?>
					<div class="col-md-10">
						<?php echo $page->field('slug', array(
							'class' => 'form-control slug',
							'prefix' => 'page'
						)); ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-10">
						<div class="checkbox">
							<?php echo Form::checkbox('page[use_redirect]', 1, (bool) $page->use_redirect, array(
								'id' => 'page_use_redirect'
							)); ?>
							<?php echo $page->label('use_redirect'); ?>
						</div>
					</div>
				</div>

				<div class="form-group" id="redirect-to-container">
					<?php echo $page->label('redirect_url', array('class' => 'control-label col-md-2')); ?>
					<div class="col-md-10">
						<?php echo $page->field('redirect_url', array(
							'class' => 'form-control',
							'prefix' => 'page'
						)); ?>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<hr class="no-margin-vr" />
			<div class="tab-content no-padding-vr">
				<div class="tab-pane active" id="page-content-panel">
					
					<?php Observer::notify('view_page_edit_plugins_top', $page); ?>
					<?php Observer::notify('view_page_edit_plugins', $page); ?>
					
					<div class="panel-body">
						<?php if($action != 'add' AND $page->loaded()): ?>
						<hr class="panel-wide" />
						<?php if (isset($page->updated_on)): ?>
						<?php echo UI::label(__('Last updated by :anchor on :date', array(
							':anchor' => HTML::anchor(Route::get('backend')->uri(array(
									'controller' => 'users',
									'action' => 'edit', 
									'id' => $page->updator->id
								)), $page->updator->username),
							':date' => Date::format($page->updated_on, 'D, j F Y'))), 'important'); ?>
						<?php endif; ?>

						<?php echo HTML::anchor($page->get_frontend_url(), UI::label(UI::icon('globe') . ' ' . __('View page')), array(
							'class' => 'item-preview', 'target' => '_blank'
						)); ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="tab-pane fade" id="page-meta-panel">
					<?php echo View::factory('page/blocks/meta', array(
					'page' => $page,
					'action' => $action
				)); ?>
				</div>

				<div class="tab-pane fade" id="page-options-panel">
					<?php echo View::factory('page/blocks/sidebar', array(
						'page' => $page,
						'permissions' => $permissions,
						'action' => $action,
						'page_permissions' => $page_permissions
					)); ?>
				</div>
			</div>

			<div class="form-actions panel-footer">
				<?php echo UI::actions($page_name); ?>
			</div>
		</div>
	<?php echo Form::close(); ?>
</div>