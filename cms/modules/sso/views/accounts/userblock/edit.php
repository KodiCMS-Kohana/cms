<?php if($user->id == Auth::get_id()): ?>
<div class="panel-heading">
	<span class="panel-title"><?php echo __('Social accounts'); ?></span>
</div>

<?php if(count($socials) > 0): ?>
<div class="panel-body">
	<h4><?php echo __('Linked social accounts'); ?></h4>
	
	<div class="row social-accounts-linked">
	<?php foreach($socials as $social): ?>
		<?php $linked[] = $social->provider(); ?>
		<div class="col-xs-2 text-center">
			<?php echo HTML::image($social->avatar(), array('class' => 'img-polaroid')); ?><br />
			<strong><?php echo $social->link(); ?></strong>
			<br />
			<?php echo UI::button(__('Disconnect'), array(
				'class' => 'btn btn-xs btn-warning',
				'href' => Route::get('accounts-auth')->uri(array(
					'directory' => 'oauth', 
					'controller' => $social->provider(), 
					'action' => 'disconnect'))
			)) ?>
		</div>
	<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>

<div class="panel-body">
	<h4><?php echo __('List of supported OAuth providers'); ?></h4>

	<div class="note note-info no-margin-vr">
		<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('Binding account to an account in a social network will allow to enter the site with a single click. You can bind the account to several accounts. :settings_link', array(':settings_link' => HTML::anchor($settings_link, __('Settings')))); ?>
	</div>
	
	<div class="btn-group">
		<?php foreach ($providers as $provider => $data): ?>
		<?php if(in_array($provider, $linked)) continue; ?>
		<?php echo UI::button(UI::icon($provider) . ' ' . Arr::path($params, $provider.'.name'), array(
			'class' => 'btn btn-social-'.$provider,
			'href' => Route::get('accounts-auth')->uri(array(
				'directory' => 'oauth', 
				'controller' => $provider, 
				'action' => 'connect'))
		)) ?>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>