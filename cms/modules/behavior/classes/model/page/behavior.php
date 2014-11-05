<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Behavior
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Page_Behavior extends Model_Page_Front {

	/**
	 * 
	 * @param string $part
	 * @param boolean $inherit
	 * @param integer $cache_lifetime
	 */
	public function content($part = 'body', $inherit = FALSE, $cache_lifetime = NULL, array $tags = array())
	{
		$method = 'content_' . URL::title($part, '_');
		if(method_exists($this, $method))
		{
			return $this->{$method}($cache_lifetime, $tags);
		}
		
		return parent::content($part, $inherit, $cache_lifetime, $tags);
	}
}