<?php
$providers = array();
foreach ($oauth as $provider => $data)
{
	 if( (isset($data['id']) AND empty($data['id']))
		OR
			(isset($data['key']) AND empty($data['key']))		
		OR 
			empty($data['secret'])
		)
		continue;
	 
	 $providers[$provider] = $data;
}
?>
<?php if(count($providers) > 0): ?>
<hr />
<h4><?php echo __('Sign-in with your social network'); ?></h4>
<div class="btn-group">
	<?php foreach ($providers as $provider => $data): ?>
	<?php echo UI::button(Arr::path($params, $provider.'.name'), array(
		'icon' => UI::icon($provider),
		'class' => 'btn btn-inverse',
		'href' => Route::url('accounts-auth', array(
			
			'directory' => 'oauth', 
			'controller' => $provider, 
			'action' => 'login'))
	)) ?>
	<?php endforeach; ?>
</div>
<?php endif; ?>