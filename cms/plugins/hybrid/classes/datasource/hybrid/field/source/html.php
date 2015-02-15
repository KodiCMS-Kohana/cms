<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Source_HTML extends DataSource_Hybrid_Field_Source {

	protected $_is_indexable = FALSE;

	protected $_props = array(
		'filter_html' => FALSE,
		'remove_empty_tags' => FALSE,
		'allowed_tags' => '<b><i><u><p><ul><li><ol>'
	);
	
	public function booleans()
	{
		return array('filter_html', 'remove_empty_tags');
	}
	
	/**
	 * 
	 * @return string
	 */
	public function default_value()
	{
		return '';
	}
	
	/**
	 * 
	 * @return int
	 */
	public function db_default_value()
	{
		return 0;
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $old
	 * @param DataSource_Hybrid_Document $new
	 */
	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new)
	{
		$html = $new->get($this->name . '_html');
		$content_id = $this->_save_html($old->get($this->name), $html);
		
		if (Valid::numeric($content_id))
		{
			$new->set($this->name, $content_id);
		}
		else
		{
			$new->set($this->name, $old->get($this->name));
		}
	}

	/**
	 * 
	 * @param DataSource_Hybrid_Document $doc
	 * @return boolean
	 */
	public function onRemoveDocument(DataSource_Hybrid_Document $doc)
	{
		return (bool) DB::delete('hybrid_html')
			->where('id', '=', (int) $doc->get($this->name));
	}
	
	/**
	 * 
	 * @param string $value
	 * @return string
	 */
	public function convert_value($value)
	{
		return (string) DB::select('content')
			->from('hybrid_html')
			->where('id', '=', (int) $value)
			->limit(1)
			->execute()
			->get('content');
	}

	/**
	 * 
	 * @param integer $id
	 * @param string $html
	 * @return boolean|integer
	 */
	protected function _save_html($id, $html)
	{
		$exists = (bool) DB::select('id')
			->from('hybrid_html')
			->where('id', '=', (int) $id)
			->limit(1)
			->execute()
			->get('id');
		
		$filtered_html = $html;

		if ($this->filter !== NULL)
		{
			$filter = WYSIWYG::get_filter($this->filter);
			$filtered_html = $filter->apply($html);
		}

		$allowed_tags = $this->allowed_tags;
		
		
		if ($this->filter_html === TRUE AND !empty($allowed_tags))
		{
			$filtered_html = Kses::filter($filtered_html, $allowed_tags);
		}

		if ($this->remove_empty_tags === TRUE)
		{
			$filtered_html = Kses::remove_empty_tags($filtered_html);
		}

		$data = array(
			'content' => $html,
			'content_html' => $filtered_html
		);
		
		if($exists)
		{
			DB::update('hybrid_html')
				->set($data)
				->where('id', '=', (int) $id)
				->execute();
			
			return $id;
		}
		else
		{
			list($id, $count) = DB::insert('hybrid_html')
				->columns(array_keys($data))
				->values($data)
				->execute();
			
			return $id;
		}
	}

	public function get_type()
	{
		return 'INT(11)';
	}
	
	public static function fetch_widget_field($widget, $field, $row, $fid)
	{
		return !empty($row[$fid]) 
			? array(
				'content' => $row[$fid . '::content'],
				'content_html' => $row[$fid . '::content_html']
			)
			: array(
				'content' => '',
				'content_html' => ''
			);
	}
	
	public function get_query_props(\Database_Query $query, DataSource_Hybrid_Agent $agent)
	{
		parent::get_query_props($query, $agent);
		
		$allias = 'html::' . $this->id;

		$query->join(array('hybrid_html', $allias), 'left')
			->on($this->name, '=', $allias . '.id')
			->select(array($allias . '.content', $this->id . '::content'))
			->select(array($allias . '.content_html', $this->id . '::content_html'));
	}
}