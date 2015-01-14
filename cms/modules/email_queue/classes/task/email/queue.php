<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Задача отправки отложенных писем
 * 
 * @package		KodiCMS/Email
 * @category	Task
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Task_Email_Queue extends Minion_Task
{
	protected $_options = array(
		'use_sleep' => 0
	);

	protected function _execute(array $params)
	{
		try
		{
			if ($params['use_sleep'] == 1)
			{
				$status = Email_Queue::batch_send_with_sleep();
			}
			else
			{
				$status = Email_Queue::batch_send();
			}

			Minion_CLI::write('============ Report ==========');
			Minion_CLI::write(__('Total emails sent - :num', array(':num' => $status['sent'])));
			Minion_CLI::write(__('Total emails failed - :num', array(':num' => $status['failed'])));
		}
		catch (Exception $e)
		{
			Minion_CLI::write($e->getMessage());
		}
	}
}