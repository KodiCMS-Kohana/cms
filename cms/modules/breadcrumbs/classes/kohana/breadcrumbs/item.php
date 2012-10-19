<?php defined('SYSPATH') OR die('No direct script access!'); 

class Kohana_Breadcrumbs_Item {
	
	public $url = FALSE;
	
	public $name = '';
	
	public $active = TRUE;
	
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
	
	protected function set_url($url)
	{
		$this->url = url::site($url);
	}	
}