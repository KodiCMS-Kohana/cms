<?php echo Form::open(($action == 'edit') ? 'layout/edit/'. $layout->name : 'layout/add/', array(
	'id' => 'layoutEditForm', 'class' => 'form-horizontal')); ?>

	<?php echo Form::hidden('token', Security::token()); ?>
	<div class="widget widget-nopad">
		<div class="widget-title">
			<div class="control-group">
				<label class="control-label title" for="layoutEditNameField"><?php echo __('Layout name'); ?></label>
				<div class="controls">
					<?php echo Form::input('name', $layout->name, array(
						'class' => 'slug focus input-title', 'id' => 'layoutEditNameField',
						'tabindex'	=> 1
					)); ?>
				</div>
			</div>
		</div>
		<div class="widget-header widget-inverse">
			<h4><?php echo __('Content'); ?></h4>
		</div>
		<div class="widget-content">
			<?php echo Form::textarea('content', $layout->content, array(
				'tabindex'		=> 2,
				'id'			=> 'textarea_content'
			)); ?>
		</div>
		<div class="form-actions widget-footer">
			<?php echo UI::actions($page_name); ?>
		</div>
	</div>
<?php echo Form::close(); ?>
<!--/#layoutEditForm-->