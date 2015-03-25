<?php defined('SYSPATH') or die('No direct script access.');

$config = Kohana::$config->load('installer');
$data = $config->get('user_meta', array());

foreach ($data as $key => $value)
{
	Model_User_Meta::set($key, $value, 0);
}