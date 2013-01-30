<?php defined('SYSPATH') or die('No direct script access.');
return array
(
	'file'    => array(
		'driver'             => 'file',
		'cache_dir'          =>  CMSPATH.'cache',
		'default_expire'     => 3600,
		'ignore_on_delete'   => array(
			'.gitignore',
			'.git',
			'.svn'
		)
	)
);
