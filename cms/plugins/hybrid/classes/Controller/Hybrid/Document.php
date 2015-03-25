<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Hybrid
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Hybrid_Document extends Controller_System_Datasource_Document
{
	protected function _load_template($doc) 
	{
		parent::_load_template($doc);
		
		$this->template->content->set(array(
			'fields' => $this->section()->record()->fields()
		));
	}
	
	protected function _load_session_data($doc)
	{
		$doc = parent::_load_session_data($doc);

		if ($doc->loaded())
		{
			return $doc->convert_values();
		}
		else
		{
			return $doc->default_values();
		}
	}
}