<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );



/**
 * @package		KodiCMS/Search
 * @category	Drivers
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Search_Sphinx extends Search {

	/**
	 *
	 * @var SphinxClient 
	 */
	protected $_client = NULL;
		
	public function __construct(array $config)
	{
		parent::__construct($config);
		
		Kohana::load(Kohana::find_file('vendors', 'sphinxapi'));
		
		$this->_client = new SphinxClient();
		$this->_client->SetServer($this->config('host'), $this->config('port'));
	}

	/**
	 * 
	 * @param string $keyword
	 * @param boolean $only_title
	 * @param string $modules
	 * @param integer $limit
	 * @param integer $offset
	 * @return array
	 */
	public function find_by_keyword($keyword, $only_title = FALSE, $modules = NULL, $limit = 50, $offset = 0)
	{
		if (Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('Search', __FUNCTION__);
		}
		
		echo debug::Vars($this->_client->Query($keyword), $this->_client->GetLastError());
		$ids = array();

		if (isset($benchmark)) 
		{
			Profiler::stop($benchmark);
		}
		
		return $ids;
	}
	
	/**
	 * 
	 * @param string $keyword
	 * @param boolean $only_title
	 * @param string $modules
	 * @return integer
	 */
	public function count_by_keyword($keyword, $only_title = FALSE, $modules = NULL)
	{
		return 0;
	}

	/**
	 * 
	 * @param string $module
	 * @param integer $id
	 * @param string $title
	 * @param string $content
	 * @param array $params
	 * @return bool
	 */
	public function add_to_index($module, $id, $title, $content = '', $annotation, $params = array())
	{
		
	}

	/**
	 * 
	 * @param string $module
	 * @param integer $id
	 * @return bool
	 */
	public function remove_from_index($module, $id = NULL)
	{
		
	}

}