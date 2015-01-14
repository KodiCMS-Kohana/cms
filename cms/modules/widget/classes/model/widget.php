<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Widgets
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget extends ORM {

	protected $_reload_on_wakeup = FALSE;
	
	protected $_created_column = array(
		'format' => 'Y-m-d H:i:s',
		'column' => 'created_on'
	);

	/**
	 *
	 * @var Model_Widget_Decorator 
	 */
	protected $_code = FALSE;

	public function rules() 
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('min_length', array(':value', 2)),
				array('max_length', array(':value', 100))
			)
		);
	}
	
	public function type()
	{
		$widget_types = Widget_Manager::map();

		$type = $this->type;

		foreach ($widget_types as $group => $types)
		{
			if (isset($types[$type]))
			{
				$type = $types[$type];
			}
		}

		return $type;
	}
	
	public function code()
	{
		if ($this->_code === FALSE)
		{
			try
			{
				$this->_code = Kohana::unserialize($this->code);
			}
			catch (Exception $e)
			{
				$this->_code = new Model_Widget_HTML();
			}

			$this->_code->id = $this->id;
		}

		return $this->_code;
	}
	
	public function filter()
	{
		$request = Request::initial();

		$types = (array) $request->query('widget_type');

		if (!empty($types))
		{
			$this->where('type', 'in', $types);
		}

		return $this;
	}
	
	/**
	 * 
	 * @return array
	 * @throws Kohana_Exception
	 */
	public function locations()
	{
		if (!$this->loaded())
		{
			throw new Kohana_Exception('Cannot find locations, because widget model is not loaded.');
		}

		$query = DB::select()
			->from('page_widgets')
			->execute()
			->as_array();
		
		$pages_widgets = array(); // занятые блоки для исключения из списков
		$page_widgets = array(); // выбранные блоки для текущего виджета
		
		foreach ($query as $widget)
		{
			if ($widget['widget_id'] == $this->id)
			{
				$page_widgets[$widget['page_id']] = array($widget['block'], $widget['position']);
			}
			else
			{
				$pages_widgets[$widget['page_id']][$widget['block']] = array($widget['block'], $widget['position']);
			}
		}
		
		return array($page_widgets, $pages_widgets);
	}
}