<?php defined('SYSPATH') or die('No direct access allowed.');

Core::load(PLGPATH . 'poll/vendors/flatfile.php');
Core::load(PLGPATH . 'poll/config.php');

Route::set('send_poll', 'send_poll')
	->defaults( array(
		'controller' => 'poll',
		'action' => 'send',
	) );