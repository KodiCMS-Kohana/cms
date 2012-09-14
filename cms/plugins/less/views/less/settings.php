
<div class="control-group">
	<div class="checkbox">
		<label><?php echo Form::checkbox('setting[enabled]', 'yes', $plugin->get('enabled', 'no') == 'yes'); ?> <?php echo __('Enable compiler'); ?></label>
	</div>

	<div class="checkbox">
		<label><?php echo Form::checkbox('setting[format_css]', 'yes', $plugin->get('format_css', 'no') == 'yes'); ?> <?php echo __('Format css code'); ?></label>
	</div>

</div>

<fieldset>
	<legend><?php echo __('Paths'); ?></legend>
	<div class="control-group <?php if(!$is_dir_less): ?>error<?php endif; ?>">
		<label class="control-label"><?php echo __('Less folder path'); ?></label>
		<div class="controls">
			<?php echo Form::input('setting[less_folder_path]', $plugin->get('less_folder_path', $less_folder_path), array(
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
			<?php echo Form::input('setting[css_folder_path]', $plugin->get('css_folder_path', $css_folder_path), array(
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