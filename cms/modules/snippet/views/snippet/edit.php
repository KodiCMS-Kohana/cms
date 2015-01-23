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
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Content'); ?></span>
		<?php echo UI::badge($snippet->get_relative_path()); ?>
		<?php if ($snippet->is_writable() OR ! $snippet->is_exists()): ?>
		<div class="panel-heading-controls">
			<?php echo UI::button(__('File manager'), array(
				'class' => 'btn-filemanager btn-flat btn-info btn-sm', 
				'data-el' => 'textarea_content',
				'icon' => UI::icon( 'folder-open'),
				'data-hotkeys' => 'ctrl+m'
			)); ?>
		</div>
		<?php endif; ?>
	</div>
	<?php echo Form::textarea('content', $snippet->content, array(
		'id'			=> 'textarea_content',
		'tabindex'		=> 2,
		'data-height'	=> 600,
		'data-readonly'	=> ( ! $snippet->is_exists() OR ($snippet->is_exists() AND $snippet->is_writable())) ? 'off' : 'on'
	)); ?>

	<?php if($snippet->is_exists() AND !$snippet->is_writable()): ?>
	<div class="alert alert-danger alert-dark no-margin-b">
		<?php echo __('Snippet is not writeable'); ?>
	</div>
	<?php elseif (ACL::check('snippet.edit')): ?>
	<div class="form-actions panel-footer">
		<?php echo UI::actions($page_name); ?>
	</div>
	<?php endif; ?>
<?php echo Form::close(); ?>