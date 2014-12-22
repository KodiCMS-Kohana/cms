<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Tags
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Model_Tag extends Record
{
    const TABLE_NAME = 'tags';
	const SEPARATOR = ',';
	
//	protected $_primary_key = 'name';
	
	public static function findAllLike($tag)
	{
		return Record::findAllFrom(static::calledClass(), array(
			'or_where' => array(
				array('name', 'like', '%:query%')
			),
			'order_by' => array(
				array('count', 'desc'),
			)
		), 
		array(
			':query' => DB::expr($tag)
		));
	}
}