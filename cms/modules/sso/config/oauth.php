<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Configuration for OAuth providers.
 */
return array(
	'accounts' => array(
		// http://vk.com/editapp?act=create
		 'vk' => array(
			'id' => NULL,
			'secret' => NULL
		 ),

		// https://dev.twitter.com/apps/new
		'twitter' => array(
			'key' => NULL,
			'secret' => NULL
		 ),

		// https://developers.facebook.com/apps?ref=mb
		'facebook' => array(
			'id' => NULL,
			'secret' => NULL
		 ),

		// https://yandex.ru/client/new
		'yandex' => array(
			'id' => NULL,
			'secret' => NULL
		 ),

		// https://yandex.ru/client/new
		'google' => array(
			'key' => NULL,
			'secret' => NULL
		 ),

		// https://github.com/settings/applications/new
		'github' => array(
			'id' => NULL,
			'secret' => NULL
		 ),

		// http://disqus.com/api/applications/register/
		'disqus' => array(
			'id' => NULL,
			'secret' => NULL
		 ),
	)
);