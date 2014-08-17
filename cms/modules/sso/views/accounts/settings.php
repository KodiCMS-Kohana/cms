<div class="panel-heading" data-icon="comments">
	<span class="panel-title" id="social-accounts-settings"><?php echo __('Social accounts settings'); ?></span>
</div>
<div class="panel-body social-accounts-settings">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Enable registration'); ?></label>
		<div class="col-md-2">
			<?php echo Form::select('setting[oauth][register]', Form::choices(), Config::get('oauth', 'register')); ?>
		</div>
	</div>

	<?php foreach ($oauth as $provider => $data): ?>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h5 class="panel-title">
				<?php echo UI::icon($provider); ?>&nbsp;&nbsp;&nbsp;
				<?php echo Arr::path($params, $provider.'.name'); ?>&nbsp;
				<?php if(Arr::path($params, $provider.'.create_link')): ?>
				<small><?php echo HTML::anchor(Arr::path($params, $provider.'.create_link'), NULL, array(
					'target' => 'blank'
				)); ?></small>
				<?php endif; ?>
			</h5>
		</div>
		<div class="panel-body">
			<?php foreach ($data as $key => $value): ?>
			<div class="form-group">
				<label class="control-label col-md-3"><?php echo strtoupper($key); ?></label>
				<div class="col-md-3">
					<?php echo Form::input('setting[oauth][accounts][' . $provider . '][' . $key . ']', $value, array(
						'class' => 'form-control'
					)); ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endforeach; ?>
</div>