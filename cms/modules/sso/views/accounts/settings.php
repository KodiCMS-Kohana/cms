<div class="widget-header spoiler-toggle" data-spoiler=".social-accounts-settings" data-hash="social-accounts-settings">
	<h3 id="social-accounts-settings"><?php echo __('Social accounts settings'); ?> <?php echo UI::icon( 'chevron-down spoiler-toggle-icon' ); ?></h3>
</div>
<div class="widget-content spoiler social-accounts-settings">
	<?php foreach ($oauth as $provider => $data): ?>
	
	<h5><?php echo Arr::path($params, $provider.'.name'); ?> <?php if(Arr::path($params, $provider.'.create_link')): ?>(<?php echo HTML::anchor(Arr::path($params, $provider.'.create_link'), NULL, array(
		'target' => 'blank'
	)); ?>)<?php endif; ?></h5>

	<?php foreach ($data as $key => $value): ?>
	<div class="control-group">
		<label class="control-label"><?php echo strtoupper($key); ?></label>
		<div class="controls">
			<?php echo Form::input( 'setting[oauth.'.$provider.'.'.$key.']', Setting::get('oauth.'.$provider.'.'.$key), array(
				'class' => 'input-xxlarge'
			)); ?>
		</div>
	</div>
	<?php endforeach; ?>

	<hr />
	<?php endforeach; ?>
	
	
</div>
