<?php defined('SYSPATH') OR die('No direct access allowed.');

$route = (IS_BACKEND ? '('.ADMIN_DIR_NAME.'/)' : '') . '<directory>/<controller>/<action>';
$actions = array(
	'identify',
	'login', 'complete_login',
	'register', 'complete_register',
	'connect', 'complete_connect',
	'disconnect', 'complete_disconnect'
);

Route::set('accounts-auth', $route, array(
	'directory' => '(openid|oauth)', 
	'action' => '('.implode('|', $actions).')'
));

Observer::observe('view_user_edit_plugins', function($user) {
	
	$providers  = array();
	
	foreach (Kohana::$config->load('oauth') as $provider => $data)
	{
		 if(
				(isset($data['id']) AND empty($data['id']))
			OR
				(isset($data['key']) AND empty($data['key']))		
			OR 
				empty($data['secret'])
			)
			continue;

		 $providers[$provider] = $data;
	}
	echo View::factory('accounts/userblock/edit', array(
		'user' => $user,
		'settings_link' => Route::url('backend', array(
			'controller' => 'setting')
		) . '#social-accounts-settings',
		'params' => Kohana::$config->load('social')->as_array(),
		'socials' => $user->socials->find_all(),
		'providers' => $providers,
		'linked' => array()
	));
});

Observer::observe('admin_login_form_after_button', function() {
	echo View::factory('accounts/userblock/login', array(
		'oauth' => Kohana::$config->load('oauth'),
		'params' => Kohana::$config->load('social')->as_array()
	));
});

if(IS_BACKEND)
{
	Observer::observe('view_setting_plugins', function() {
		echo View::factory('accounts/settings', array(
			'oauth' => Kohana::$config->load('oauth'),
			'params' => Kohana::$config->load('social')->as_array()
		));
	});

	Observer::observe('save_settings', function($post) {
		if(!isset($post['setting']['oauth_register'])) 
		{
			Setting::set( 'oauth_register', 0 );
			Setting::save();
		}
	});
}