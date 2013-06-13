<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Configuration for OAuth providers.
 */
return array(
	// http://vk.com/editapp?act=create
	 'vk' => array(
		'id' => Setting::get('oauth.vk.id'),
		'secret' => Setting::get('oauth.vk.secret')
	 ),
	
	// https://dev.twitter.com/apps/new
	'twitter' => array(
		'key' => Setting::get('oauth.twitter.key'),
		'secret' => Setting::get('oauth.twitter.secret')
	 ),
	
	// https://developers.facebook.com/apps?ref=mb
	'facebook' => array(
		'id' => Setting::get('oauth.facebook.id'),
		'secret' => Setting::get('oauth.facebook.secret')
	 ),
	
	// https://oauth.yandex.ru/client/new
	'yandex' => array(
		'id' => Setting::get('oauth.yandex.id'),
		'secret' => Setting::get('oauth.yandex.secret')
	 ),
	
	// https://oauth.yandex.ru/client/new
	'google' => array(
		'key' => Setting::get('oauth.google.id'),
		'secret' => Setting::get('oauth.google.secret')
	 ),
	
	// https://github.com/settings/applications/new
	'github' => array(
		'id' => Setting::get('oauth.github.id'),
		'secret' => Setting::get('oauth.github.secret')
	 ),
	
	// http://disqus.com/api/applications/register/
	'disqus' => array(
		'id' => Setting::get('oauth.disqus.id'),
		'secret' => Setting::get('oauth.disqus.secret')
	 ),
);