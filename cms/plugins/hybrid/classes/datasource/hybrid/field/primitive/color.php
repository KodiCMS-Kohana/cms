<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Primitive_Color extends DataSource_Hybrid_Field_Primitive {

	protected $_props = array(
		'default' => '#ffffff'
	);
	
	public function get_type() 
	{
		return 'VARCHAR (7) NOT NULL';
	}
	
	public function onValidateDocument(Validation $validation, DataSource_Hybrid_Document $doc)
	{
		$validation->rule($this->name, 'color');
			
		return parent::onValidateDocument($validation, $doc);
	}
	
	public function fetch_headline_value($value, $document_id)
	{
		if (!empty($value))
		{
			return UI::icon('tag fa-lg', array(
				'style' => 'color: ' . $value
			));
		}

		return parent::fetch_headline_value($value, $document_id);
	}
	
	public function onControllerLoad()
	{
		Assets::package('colorpicker');
		parent::onControllerLoad();
	}
}