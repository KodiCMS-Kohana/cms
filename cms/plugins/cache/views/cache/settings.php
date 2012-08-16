<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<div class="page-header">
	<h1><?php echo __('Cache settings'); ?></h1> 
</div>

<ul class="breadcrumb">
	<li><a href="<?php echo URL::site('plugins'); ?>"><?php echo __('Plugins'); ?></a> <span class="divider">/</span></li>
	<li class="active"><?php echo __('Cache settings'); ?></li>
</ul>

<form class="form-horizontal" action="<?php echo URL::site('plugin/cache/settings'); ?>" method="post">
	<fieldset>
		<legend><?php echo __('Cache'); ?></legend>
		<div class="control-group">
			<label class="control-label" for="CSTypeRadio-static"><?php echo __('Switch On'); ?></label>
			<div class="controls">
				<?php echo Form::checkbox('setting[cache_static]', 'yes', (isset($setting['cache_static']) && $setting['cache_static'] == 'yes'), array(
					'id' => 'CSTypeRadio-static'
				)); ?>
			</div>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<button id="CSRemoveButton" class="btn btn-danger" rel="<?php echo URL::site('plugin/cache/remove_cache'); ?>">
					<?php echo HTML::icon('trash icon-white'); ?> 
					<?php echo __('Remove cached data'); ?>
				</button>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="CSLifetime"><?php echo __('Cache life time'); ?></label>
			<div class="controls">
				<?php echo Form::input('setting[cache_lifetime]', (isset($setting['cache_lifetime']) ? (int)$setting['cache_lifetime']: 86400), array(
					'class' => 'input-xlarge', 'id' => 'CSLifetime'
				)); ?>
				<p class="help-block"><?php echo __('Time when cache will be updated. Default: 24*60*60 = 86400 seconds.'); ?></p>
			</div>
		</div>
	
		
		<div class="control-group">
			<label class="control-label" for="CSRemoveStaticCheckbox"><?php echo __('Removing static cache automaticly'); ?></label>
			<div class="controls">
				<?php echo Form::checkbox('setting[cache_remove_static]', 'yes', (isset($setting['cache_remove_static']) && $setting['cache_remove_static'] == 'yes'), array(
					'id' => 'CSRemoveStaticCheckbox'
				)); ?>
				<p class="help-block"><?php echo __('When update or save page – all static cache will be removed automaticly.'); ?></p>
			</div>
		</div>
	</fieldset>

	<div class="form-actions">
		<?php echo Form::button('submit', HTML::icon('ok') .' '. __('Save setting'), array(
			'class' => 'btn btn-large btn-success'
		)); ?>
	</div>

</form>