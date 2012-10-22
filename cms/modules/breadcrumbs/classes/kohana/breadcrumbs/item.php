<?php defined('SYSPATH') OR die('No direct script access!'); 

class Kohana_Breadcrumbs_Item {
	
	/**
	 *
	 * @var string 
	 */
	public $url = FALSE;
	
	/**
	 *
	 * @var string
	 */
	public $name = '';
	
	/**
	 *
	 * @var boolean
	 */
	public $active = TRUE;
	
	/**
	 * 
	 * @param boolean $urls
	 * @param string $name
	 * @param string $url
	 * @param boolean $active
	 * @throws Kohana_Exception
	 */
	public function __construct($urls, $name, $url = NULL, $active = FALSE)
	{
		if(empty($name))
		{
			throw new Kohana_Exception('Breadcrumbs: The breadcrumb name could not be empty!');
		}
		$this->name = $name;
		if($urls && $url !== FALSE)
		{
			if(empty($url))
			{
				$url = $name;
			}

			$this->set_url($url);
		}

		$this->active = $active;
	}
	
	/**
	 * 
	 * @param string $url
	 */
	protected function set_url($url)
	{
		$this->url = url::site($url);
		return $this;
	}	
}