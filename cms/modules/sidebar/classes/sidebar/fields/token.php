<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Sidebar
 * @category	Fields
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Sidebar_Fields_Token extends Sidebar_Fields_Hidden {

	public function __construct($field = array(), $render = TRUE) 
	{
		if (!isset($field['value']))
		{
			$field['value'] = Security::token();
		}

		if (!isset($field['name']))
		{
			$field['name'] = 'security_token';
		}

		parent::__construct($field, $render);
	}
}