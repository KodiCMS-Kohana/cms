<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
abstract class DataSource_Hybrid_Field_Source_OneToOne extends DataSource_Hybrid_Field_Source {
	
	public $from_ds = NULL;
	
	protected $_widget_types = array('hybrid_document');

	/**
	 * 
	 * @param array $row
	 * @param integr $fid
	 * @param integer $recurse
	 * @return array
	 */
	protected static function _fetch_related_widget($widget, $row, $fid, $recurse, $key = 'ids', $fetch = FALSE)
	{
		$widget_id = Arr::get($widget->doc_fetched_widgets, $fid);

		if (empty($widget_id))
		{
			return NULL;
		}

		$widget = Context::instance()->get_widget($widget_id);

		if (!$widget)
		{
			$widget = Widget_Manager::load($widget_id);
		}

		if ($widget === NULL)
		{
			return array();
		}

		$widget->{$key} = $row[$fid];

		if ($fetch === FALSE)
		{
			return $widget->get_document($row[$fid], $recurse - 1);
		}
		else
		{
			return $widget->fetch_data();
		}
	}

	public function onValidateDocument( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		$validation->rule($this->name, 'numeric');
		
		return parent::onValidateDocument($validation, $doc);
	}
}