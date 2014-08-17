<?php if(count($providers) > 0): ?>
<div class="panel margin-sm-vr">
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Sign-in with your social network'); ?></span>
	</div>
	<div class="panel-body">
		<div class="btn-group">
			<?php foreach ($providers as $provider => $data): ?>
			<?php echo UI::button(Arr::path($params, $provider.'.name'), array(
				'icon' => UI::icon($provider),
				'class' => 'btn btn-inverse',
				'href' => Route::get('accounts-auth')->uri(array(

					'directory' => 'oauth', 
					'controller' => $provider, 
					'action' => 'login'))
			)) ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<?php endif; ?>