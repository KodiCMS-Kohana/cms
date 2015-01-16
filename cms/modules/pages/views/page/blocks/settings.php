<?php Observer::notify('view_page_edit_sidebar_before', $page); ?>

<?php
$layout_name = $page->layout();
$layout_link = '';

if ((ACL::check('layout.edit') OR ACL::check('layout.view')) AND ! empty($layout_name))
{
	$layout_link = HTML::anchor(Route::get('backend')->uri(array(
		'controller' => 'layout', 
		'action' => 'edit', 
		'id' => $layout_name
	)), $layout_name, array(
		'class' => 'popup fancybox.iframe'
	));
}
?>

<div class="panel-body">
	<?php if ($page->id != 1): ?>
	<div class="form-group">
		<?php echo $page->label('parent_id', array('class' => 'control-label col-md-3')); ?>
		<div class="col-md-6">
			<?php echo $page->field('parent_id', array(
				'prefix' => 'page'
			)); ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="form-group">
		<?php echo $page->label('layout_file', array('class' => 'control-label col-md-3')); ?>
	
		<div class="col-md-6">
			<?php echo $page->field('layout_file', array(
				'prefix' => 'page'
			)); ?>
		</div>

		<div class="col-md-3">
			<?php if (!empty($layout_name)): ?>
			<?php echo UI::label(__('Current layput: :name', array(':name' => $layout_link))); ?>
			<?php else: ?>
			<?php echo UI::label(__('Layout not set'), 'danger'); ?>
			<?php endif; ?>
		</div>
		
	</div>
	
	<hr class="panel-wide" />

	<div class="form-group">
		<?php echo $page->label('behavior_id', array('class' => 'control-label col-md-3')); ?>
		<div class="col-md-6">
			<?php echo $page->field('behavior_id', array(
				'prefix' => 'page'
			)); ?>
			<div id="behavor_options"></div>
		</div>
	</div>
	
	<hr class="panel-wide" />

	<?php if ($page->id != 1): ?>
	<div class="form-group page-statuses">
		<?php echo $page->label('status_id', array('class' => 'control-label col-md-3')); ?>
		<div class="col-md-6">
			<?php echo $page->field('status_id', array(
				'prefix' => 'page'
			)); ?>
			
			<div class="hidden password-container form-group no-margin-hr">
				<hr />
				<?php echo $page->label('password', array('class' => 'control-label')); ?>
				<?php echo $page->field('password', array(
					'prefix' => 'page',
					'class' => 'form-control'
				)); ?>
			</div>
		</div>
	</div>
	
	<hr class="panel-wide" />
	<?php endif; ?>

	<?php if ($page->id != 1): ?>
	<div class="form-group">
		<?php echo $page->label('published_on', array('class' => 'control-label col-md-3')); ?>
		<div class="col-md-6">
			<?php echo $page->field('published_on', array(
				'prefix' => 'page',
				'class' => 'form-control datetimepicker'
			)); ?>
		</div>
	</div>
	<hr class="panel-wide" />
	<?php endif; ?>

	<?php if (ACL::check('page.permissions')): ?>
	<div class="form-group">
		<?php echo $page->label('needs_login', array('class' => 'control-label col-md-3')); ?>
		<div class="col-md-6">
			<?php echo $page->field('needs_login', array(
				'prefix' => 'page'
			)); ?>
		</div>
	</div>
	<?php endif; ?>
</div>

<?php if (ACL::check('page.permissions')): ?>
<div class="panel-heading">
	<?php echo $page->label('page_permissions', array('class' => 'panel-title')); ?>
</div>
<div class="panel-body">
	<?php echo Form::select('page_permissions[]', $permissions, array_keys($page_permissions)); ?>
</div>
<?php endif; ?>
<?php Observer::notify('view_page_edit_sidebar_after', $page); ?>