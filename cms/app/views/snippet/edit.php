<?php echo Form::open(($action == 'edit')  ? 'snippet/edit/'. $snippet->name  : 'snippet/add/' . $snippet->name, array(
	'id' => 'snippetEditForm', 'class' => 'form-horizontal')); ?>
	<div class="widget widget-nopad">
		<?php echo Form::hidden('token', Security::token()); ?>	
		<div class="widget-title">
			<div class="control-group">
				<label class="control-label title" for="snippetEditNamelabel"><?php echo __('Snippet name'); ?></label>
				<div class="controls">
					<?php echo Form::input('name', $snippet->name, array(
						'class' => 'slug focus input-title', 'id' => 'snippetEditNamelabel',
						'tabindex'	=> 1
					)); ?>
				</div>
			</div>
		</div>
		<div class="widget-content">
			<div class="title-content"><?php echo __('Content'); ?></div>
			<?php echo Form::textarea('content', $snippet->content, array(
					'id'			=> 'textarea_content',
					'tabindex'		=> 2,
				)); ?>
		</div>
		<div class="form-actions widget-footer">
			<?php echo UI::actions($page_name); ?>
		</div>
	</div>
<?php echo Form::close(); ?>
<!--/#snippetEditForm-->