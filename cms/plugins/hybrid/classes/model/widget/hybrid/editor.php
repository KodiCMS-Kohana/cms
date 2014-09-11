<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Hybrid_Editor extends Model_Widget_Hybrid_Document {

	protected $_use_caching = FALSE;
	
	/**
	 * 
	 * @return array [$fields, $datasource, $document]
	 */
	public function fetch_data()
	{
		$datasource = Datasource_Data_Manager::load($this->ds_id);
		
		if($datasource === NULL) 
		{
			return array();
		}
		
		$id = $this->get_doc_id();
		
		if(empty($id)) 
		{
			$document = $datasource->get_empty_document();
		}
		else
		{
			$document = $datasource->get_document($id);
			
			if( ! $document )
			{
				if($this->throw_404)
				{
					$this->_ctx->throw_404();
				}
				
				$document = $datasource->get_empty_document();
			}
		}
		
		return array(
			'fields' => $datasource->record()->fields(),
			'datasource' => $datasource,
			'document' => $document
		);
	}
}