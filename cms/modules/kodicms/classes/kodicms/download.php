<?php defined('SYSPATH') OR die('No direct script access.');

class KodiCMS_Download {
	
	public static function secure_path($path)
	{
		return Encrypt::instance()->encode($path);
	}
	
	public static function decode_path($path)
	{
		return Encrypt::instance()->decode($path);
	}
	
}
