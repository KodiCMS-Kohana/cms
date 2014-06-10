<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package		KodiCMS
 * @category	Form
 * @author		ButscHSter
 */
class KodiCMS_Form extends Kohana_Form {

	public static function choices()
	{
		return array(
			Config::NO => __( 'No' ),
			Config::YES => __( 'Yes' )
		);
	}
	
}
