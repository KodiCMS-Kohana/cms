<?php defined('SYSPATH') OR die('No direct script access.');

class KodiCMS_Form extends Kohana_Form {

	public static function choises()
	{
		return array(
			Config::YES => __( 'Yes' ), 
			Config::NO => __( 'No' )
		);
	}
	
}
