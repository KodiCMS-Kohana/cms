<?php echo Form::open(Route::get('backend')->uri(array(
	'controller' => 'layout', 
	'action' => $action, 
	'id' => $layout->name)), array(
		'id' => 'layoutEditForm', 
		'class' => 'form-horizontal panel')); ?>

	<?php echo Form::token('token'); ?>
	<?php echo Form::hidden('layout_name', $layout->name); ?>

	<div class="panel-heading">
		<div class="form-group form-group-lg">
			<label for="layout-input-name" class="col-sm-2 control-label"><?php echo __('Layout name'); ?></label>
			<div class="col-sm-10">
				<div class="input-group">
					<?php echo Form::input('name', $layout->name, array(
						'class' => 'slug form-control', 
						'id' => 'layout-input-name',
						'tabindex'	=> 1,
						'placeholder' => __('Layout name')
					)); ?>
					<span class="input-group-addon"><?php echo EXT; ?></span>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Content'); ?></span>
		<?php echo UI::badge($layout->get_relative_path()); ?>
		
		<?php if ($layout->is_writable() OR ! $layout->is_exists()): ?>
		<div class="panel-heading-controls">
		<?php echo UI::button(__('File manager'), array(
			'class' => 'btn-default btn-filemanager',
			'data-el' => 'textarea_content',
			'icon' => UI::icon( 'folder-open'),
			'data-hotkeys' => 'ctrl+m'
		)); ?>
		</div>
		<?php endif; ?>
	</div>
	<?php echo Form::textarea('content', $layout->content, array(
		'tabindex'			=> 2,
		'id'				=> 'textarea_content',
		'data-readonly'		=> (!$layout->is_exists() OR ( $layout->is_exists() AND $layout->is_writable())) ? 'off' : 'on'
	)); ?>

	<?php if($layout->is_exists() AND !$layout->is_writable()): ?>
	<div class="panel-default alert alert-danger alert-dark no-margin-b">
		<?php echo __('Layout is not writeable'); ?>
	</div>
	<?php elseif (ACL::check('layout.edit')): ?>
	<div class="form-actions panel-footer">
		<?php echo UI::actions($page_name); ?>
	</div>
	<?php endif; ?>
<?php echo Form::close(); ?>