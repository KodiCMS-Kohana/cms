<?php defined('SYSPATH') or die('No direct access allowed.');

$uri = ($action == 'edit') ? 'layout/edit/'. $layout->name : 'layout/add/';
?>

<?php echo Form::open($uri, array('id' => 'layoutEditForm', 'class' => 'form-horizontal')); ?>

	<?php echo Form::hidden('token', Security::token()); ?>

	<div class="title-block">
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
	<div class="title-content"><?php echo __('Content'); ?></div>

	<?php echo Form::textarea('content', $layout->content, array(
			'tabindex'		=> 2,
			'id'			=> 'textarea_content'
		)); ?>
	<div class="form-actions">
		<?php echo UI::actions($page_name); ?>
	</div>
<?php echo Form::close(); ?>
<!--/#layoutEditForm-->