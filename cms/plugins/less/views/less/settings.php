<div class="page-header">
	<h1><?php echo __('Less compiler settings'); ?></h1> 
</div>

<form class="form-horizontal" action="<?php echo URL::site('plugin/less/settings'); ?>" method="post">

	<?php echo Form::hidden('token', Security::token()); ?>

	<fieldset class="well">
		<div class="control-group">
			<label class="control-label"><?php echo __('Enable compiler'); ?></label>
			<div class="controls">
				<?php echo Form::checkbox('setting[enabled]', 'yes', Arr::get($settings, 'enabled', 'yes') == 'yes'); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php echo __('Format css code'); ?></label>
			<div class="controls">
				<?php echo Form::checkbox('setting[format_css]', 'yes', Arr::get($settings, 'format_css', 'no') == 'yes'); ?>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend><?php echo __('Paths'); ?></legend>
		<div class="control-group <?php if(!$is_dir_less): ?>error<?php endif; ?>">
			<label class="control-label"><?php echo __('Less folder path'); ?></label>
			<div class="controls">
				<?php echo Form::input('setting[less_folder_path]', Arr::get($settings, 'less_folder_path', $less_folder_path), array(
					'class' => 'input-xlarge'
				)); ?>
				<?php if(!$is_dir_less): ?>
				<p class="help-block"><?php echo __('Directory :dir not exists', array(':dir' => $less_folder_path)); ?></p>
				<?php endif; ?>
			</div>
		</div>
		
		<div class="control-group <?php if(!$is_dir_css): ?>error<?php endif; ?>">
			<label class="control-label"><?php echo __('Css folder path'); ?></label>
			<div class="controls">
				<?php echo Form::input('setting[css_folder_path]', Arr::get($settings, 'css_folder_path', $css_folder_path), array(
					'class' => 'input-xlarge'
				)); ?>
				<?php if(!$is_dir_css): ?>
				<p class="help-block"><?php echo __('Directory :dir not exists', array(':dir' => $css_folder_path)); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</fieldset>

	<?php if(!$is_dir_less): ?>
	<fieldset>
		<legend><?php echo __('Less files to compile'); ?></legend>
	</fieldset>
	<?php endif; ?>

	<div class="form-actions">
		<?php echo Form::button('submit', HTML::icon('ok') .' '. __('Save setting'), array(
			'class' => 'btn btn-large btn-success'
		)); ?>
	</div>

</form>