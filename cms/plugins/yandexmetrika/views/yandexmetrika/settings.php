<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<div class="page-header">
	<h1><?php echo __('Yandex metrika'); ?></h1> 
</div>

<?php echo Form::open('yandexmetrika/settings', array(
	'class' => 'form-horizontal', 'method' => 'post'
)); ?>

	<?php echo Form::hidden('token', Security::token()); ?>

	<div class="control-group">
		<?php echo Form::label('setting_counter_id', 'ID метрики:', array('class' => 'control-label')); ?>
		<div class="controls">
			<?php echo Form::input('setting[counter_id]', Plugins::getSetting('counter_id', 'yandex_metrika', 0000000), array(
				'id' => 'setting_counter_id', 'class' => '', 'maxlength' => 20, 'size' => 20
			)); ?>
		</div>
	</div>

	<div class="form-actions">
	<?php echo UI::button(__('Save setting'), array(
		'class' => 'btn btn-large btn-success', 'icon' => UI::icon('ok'),
		'name' => 'submit'
	)); ?>
	</div>
<?php echo Form::close(); ?>