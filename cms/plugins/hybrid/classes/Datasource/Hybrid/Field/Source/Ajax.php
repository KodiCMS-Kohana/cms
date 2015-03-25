<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Source_AJAX extends DataSource_Hybrid_Field_Source_Free {

	protected $_props = array(
		'url' => NULL,
		'inject_key' => 'ids',
		'preload' => FALSE
	);
	
	public function booleans()
	{
		return array('preload');
	}

	public function set_url($url)
	{
		if (!Valid::url($url))
		{
			return;
		}

		$this->url = $url;
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $document
	 * return string
	 */
	public function source_url(DataSource_Hybrid_Document $document)
	{
		$url = $this->url;
		
		$values = array(
			':document_id' => $document->id,
			':ds_id' => $document->section()->id()
		);
		
		return strtr($url, $values);
	}

	public function get_type()
	{
		return 'VARCHAR(255)';
	}
}