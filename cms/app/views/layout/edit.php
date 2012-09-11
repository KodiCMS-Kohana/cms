<?php defined('SYSPATH') or die('No direct access allowed.');

$uri = ($action == 'edit') ? URL::site('layout/edit/'. $layout->name) : URL::site('layout/add/');
?>

<div class="page-header">
	<h1><?php echo __('Layouts'); ?></h1> 
</div>

<?php echo Form::open($uri, array('id' => 'layoutEditForm', 'class' => 'form-horizontal')); ?>

	<?php echo Form::hidden('token', Security::token()); ?>

	<div class="control-group">
		<label class="control-label title" for="layoutEditNameField"><?php echo __('Layout name'); ?></label>
		<div class="controls">
			<?php echo Form::input('layout[name]', $layout->name, array(
				'class' => 'input-xlarge slug focus title', 'id' => 'layoutEditNameField',
				'tabindex'	=> 1
			)); ?>
		</div>
	</div>

	<?php echo Form::textarea('layout[content]', $layout->content, array(
			'tabindex'		=> 2,
			'id'			=> 'textarea_content'
		)); ?>
	<div class="form-actions">
		<?php echo UI::actions($page_name); ?>
	</div>
<?php echo Form::close(); ?>
<!--/#layoutEditForm-->