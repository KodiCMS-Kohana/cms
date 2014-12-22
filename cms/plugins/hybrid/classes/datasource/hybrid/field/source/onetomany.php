<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
abstract class DataSource_Hybrid_Field_Source_OneToMany extends DataSource_Hybrid_Field_Source {
	
	public $from_ds = NULL;
	
	protected $_widget_types = array('hybrid_headline');

	/**
	 * 
	 * @param array $row
	 * @param integr $fid
	 * @param integer $recurse
	 * @return array
	 */
	protected static function _fetch_related_widget( $widget, $row, $fid, $recurse, $key = 'ids', $fetch = FALSE)
	{
		$widget_id = $widget->doc_fetched_widgets[$fid];
		
		if( empty($widget_id) ) return NULL;

		$widget = Context::instance()->get_widget($widget_id);
		
		if(!$widget)
		{
			$widget = Widget_Manager::load($widget_id);
		}
		
		if($widget === NULL) return array();

		$doc_ids = explode(',', $row[$fid]);

		$widget->{$key} = $doc_ids;
		
		if($fetch === FALSE)
		{
			return $widget->get_documents( $recurse - 1);
		}
		else
		{
			return $widget->fetch_data();
		}
	}
	
	public function onValidateDocument( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		$validation->rule($this->name, array($this, 'is_valid_documents_ids'));
		
		return parent::onValidateDocument($validation, $doc);
	}
	
	public function is_valid_documents_ids($value) 
	{
		return strlen($value) == strspn($value, '0123456789,');
	}
}