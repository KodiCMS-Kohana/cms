<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Datasource
 * @category	Headline
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Datasource_Section_Hybrid_Headline extends Datasource_Section_Headline {

	/**
	 * Список полей, выводимых в списке документов
	 * 
	 * @var array 
	 */
	protected $_fields = NULL;

	public function fields()
	{
		if($this->_fields !== NULL)
		{
			return $this->_fields;
		}

		$this->_fields = parent::fields();
		
		$fields = DataSource_Hybrid_Field_Factory::get_section_fields($this->_section->id());
		
		foreach($fields as $key => $field)
		{
			$this->_fields[$field->name] = array(
				'name' =>  $field->header,
				'visible' => (bool) $field->in_headline
			);
		}
		
		$this->_fields['created_on'] = array(
			'name' => 'Date of creation',
			'width' => 150,
			'class' => 'text-right text-muted text-sm',
			'visible' => TRUE
		);

		return $this->_fields;
	}

	public function get( array $ids = NULL )
	{
		$agent = $this->_section->agent();

		$fids = array();

		$documents = array();

		$results = array(
			'total' => 0,
			'documents' => array()
		);
		
		$pagination = $this->pagination($ids);

		$section_fields = DataSource_Hybrid_Field_Factory::get_section_fields($this->_section->id());

		foreach ($section_fields as $key => $field)
		{
			if (!array_key_exists($field->name, $this->fields()))
			{
				continue;
			}

			$fids[] = $field->id;
		}

		$query = $agent
			->get_query_props($fids, (array) $this->sorting())
			->select('d.created_on')
			->select('d.created_by_id')
			->select('dss.name')
			->join(array('datasources', 'dss'))
				->on('d.ds_id', '=', 'dss.id');

		if( ! empty($ids) ) 
		{
			$query->where('d.id', 'in', $ids);
		}

		$query = $this->search_by_keyword($query);

		$result = $query
			->limit($this->limit())
			->offset($this->offset())
			->execute()
			->as_array('id');

		if(count($result) > 0)
		{
			$results['total'] = $pagination->total_items;
			
			foreach ( $result as $id => $row )
			{
				$data = array(
					'id' => $id,
					'published' => (bool) $row['published'],
					'header' => $row['header'],
					'created_on' => Date::format($row['created_on']),
					'created_by_id' => $row['created_by_id']
				);
				
				foreach($section_fields as $field)
				{
					if(isset($row[$field->id]))
					{
						$data[$field->name] = $field->fetch_headline_value($row[$field->id], $id);
					}
				}

				$document = new DataSource_Hybrid_Document($this->_section);
				$document->id = $id;
				$documents[$id] = $document
					->read_values($data)
					->set_read_only();
			}

			$results['documents'] = $documents;
		}
		
		return $results;
	}
	
	public function count_total( array $ids = NULL )
	{
		$agent = DataSource_Hybrid_Agent::instance($this->_section->id());

		$query = $this
			->_section
			->agent()
			->get_query_props(array(), $this->_sorting);

		$query = $this->search_by_keyword($query);
		
		if( ! empty($ids) ) 
		{
			$query->where('d.id', 'in', $ids);
		}
		
		return $query->select(array(DB::expr('COUNT(*)'),'total_docs'))
			->execute()
			->get('total_docs');
	}
	
	protected function _serialize()
	{
		$vars = parent::_serialize();
		unset($vars['_fields']);
		
		return $vars;
	}
}