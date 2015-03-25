<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Задача удаления старых записей
 * @package		KodiCMS/Search
 * @category	Task
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Task_Search_Reindex extends Minion_Task
{
	protected $_options = array();

	protected function _execute(array $params)
	{
		Observer::notify('update_search_index');
	}
}