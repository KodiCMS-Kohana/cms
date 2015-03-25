<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Задача удаления старых записей
 * 
 * @package		KodiCMS/Email
 * @category	Task
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Task_Email_Clean extends Minion_Task
{
	protected $_options = array();

	protected function _execute(array $params)
	{
		ORM::factory('email_queue')->clean_old();
	}
}