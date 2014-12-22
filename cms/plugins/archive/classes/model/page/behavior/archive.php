<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Archive
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Page_Behavior_Archive extends Model_Page_Front
{
	protected function setUrl()
	{
		$this->url = trim($this->parent->url . date('/Y/m/d/', strtotime($this->created_on)). $this->slug, '/');
	}

	public function title() 
	{ 
		return isset($this->time) 
			? strftime($this->title, $this->time)
			: $this->title; 
	}

	public function breadcrumb() 
	{ 
		return isset($this->time) 
			? strftime($this->breadcrumb, $this->time)
			: $this->breadcrumb; 
	}
	
} // end class PageArchive