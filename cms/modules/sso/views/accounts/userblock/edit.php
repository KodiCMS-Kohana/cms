<?php if(count($socials) > 0): ?>
<div class="widget-header">
	<h3><?php echo __('Linked social accounts'); ?></h3>
</div>
<div class="widget-content">
	<div class="row-fluid social-accounts-linked">
	<?php foreach($socials as $social): ?>
		<?php $linked[] = $social->provider(); ?>
		<div class="span2 text-center">
			<?php echo HTML::image($social->avatar(), array('class' => 'img-polaroid')); ?><br />
			<strong><?php echo $social->link(); ?></strong>
			<br />
			<?php echo UI::button(__('Disconnect'), array(
				'class' => 'btn btn-mini btn-warning',
				'href' => Route::url('accounts-auth', array(
					'directory' => 'oauth', 
					'controller' => $social->provider(), 
					'action' => 'disconnect'))
			)) ?>
		</div>
	<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>

<?php if($user->id == AuthUser::getId()): ?>
<div class="widget-header spoiler-toggle" data-spoiler=".social-accouns-binder">
	<h3><?php echo __('List of supported OAuth providers'); ?></h3>
</div>
<div class="widget-content spoiler social-accouns-binder">
	<p class="muted">
		<?php echo __('Binding account to an account in a social network will allow to enter the site with a single click. You can bind the account to several accounts. :settings_link', array(':settings_link' => HTML::anchor($settings_link, __('Settings')))); ?>
	</p>
	
	<div class="btn-group">
		<?php foreach ($providers as $provider => $data): ?>
		<?php if(in_array($provider, $linked)) continue; ?>
		<?php echo UI::button(UI::icon($provider.'-sign') . ' ' . Arr::path($params, $provider.'.name'), array(
			'class' => 'btn btn-social-'.$provider,
			'href' => Route::url('accounts-auth', array(
				'directory' => 'oauth', 
				'controller' => $provider, 
				'action' => 'connect'))
		)) ?>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>