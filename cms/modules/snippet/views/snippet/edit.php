<?php echo Form::open(Route::get('backend')->uri(array(
	'controller' => 'snippet', 
	'action' => $action, 
	'id' => $snippet->name)), array(
		'class' => 'form-horizontal panel')
); ?>

	<?php echo Form::hidden('token', Security::token()); ?>
	<?php echo Form::hidden('snippet_name', $snippet->name); ?>

	<div class="panel-heading">
		<div class="form-group form-group-lg">
			<label for="snippet-input-name" class="col-sm-2 control-label"><?php echo __('Snippet name'); ?></label>
			<div class="col-sm-10">
				<div class="input-group">
					<?php echo Form::input('name', $snippet->name, array(
						'class' => 'slug form-control', 
						'id' => 'snippet-input-name',
						'tabindex'	=> 1,
						'placeholder' => __('Snippet name')
					)); ?>
					<span class="input-group-addon"><?php echo EXT; ?></span>
				</div>
			</div>
		</div>
	</div>
	<?php if (!$snippet->is_read_only()): ?>
	<div class="panel-toggler text-center panel-heading" data-target-spoiler=".spoiler-settings">
		<?php echo UI::icon('chevron-down panel-toggler-icon'); ?> <span class="muted"><?php echo __('Settings'); ?></span>
	</div>
	<div class="panel-spoiler spoiler-settings panel-body">
		<div class="form-group">
			<label class="col-md-3 control-label"><?php echo __('WYSIWYG'); ?></label>
			<div class="col-md-9">
				<?php echo Form::select('editor', WYSIWYG::html_select(), $snippet->editor, array(
					'class' => 'form-control'
				)); ?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label"><?php echo __('Roles'); ?></label>
			<div class="col-md-9">
				<?php echo Form::select('roles[]', $roles, $snippet->get_roles(), array(
					'class' => 'form-control'
				)); ?>
			</div>
		</div>

		<hr class="panel-wide" />
	</div>
	<?php endif; ?>
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Content'); ?></span>
		<?php echo UI::badge($snippet->get_relative_path()); ?>
		<?php if (!$snippet->is_read_only()): ?>
		<div class="panel-heading-controls">
			<?php echo UI::button(__('File manager'), array(
				'class' => 'btn-filemanager btn-flat btn-info btn-sm', 
				'data-el' => 'textarea_content',
				'icon' => UI::icon('folder-open'),
				'data-hotkeys' => 'ctrl+m'
			)); ?>
		</div>
		<?php endif; ?>
	</div>
	<?php echo Form::textarea('content', $snippet->content, array(
		'class' => 'form-control',
		'id' => 'textarea_content',
		'data-height' => 600,
		'data-readonly'	=> $snippet->is_read_only() ? 'on' : 'off'
	)); ?>

	<?php if($snippet->is_read_only()): ?>
	<div class="alert alert-danger alert-dark no-margin-b">
		<?php echo __('File is not writable'); ?>
	</div>
	<?php elseif (ACL::check('snippet.edit')): ?>
	<div class="form-actions panel-footer">
		<?php echo UI::actions($page_name); ?>
	</div>
	<?php endif; ?>
<?php echo Form::close(); ?>