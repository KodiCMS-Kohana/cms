<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Reflink
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Reflink extends Controller_System_Controller {

	public function action_index()
	{
		$code = $this->request->param('code');
		if ($code === NULL)
		{
			Model_Page_Front::not_found();
		}

		$reflink_model = ORM::factory('user_reflink', $code);

		if (!$reflink_model->loaded())
		{
			Messages::errors(__('Reflink not found'));
			$this->go_home();
		}

		$next_url = Arr::get($reflink_model->data, 'next_url');

		try
		{
			Database::instance()->begin();
			Reflink::factory($reflink_model)->confirm();
			$reflink_model->delete();
			Database::instance()->commit();
		} 
		catch (Kohana_Exception $e)
		{
			Database::instance()->rollback();
			Messages::errors($e->getMessage());
		}

		if (Valid::url($next_url))
		{
			$this->go($next_url);
		}

		$this->go_home();
	}

}