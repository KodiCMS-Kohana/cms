<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi/Api
 */
class Model_API_Page_Part extends Model_API {
	
	protected $_table_name = 'page_parts';
	
	protected $_secured_columns = array(
		'content', 'content_html'
	);
	
	public function get($uids, $fields = array(), $page_id = NULL)
	{
		$uids = $this->prepare_param($uids, array('Valid', 'numeric'));
		$fields = $this->prepare_param($fields);

		$parts = DB::select('id', 'name')
			->select_array( $this->filtered_fields( $fields ) )
			->from($this->table_name());
		
		if(!empty($uids))
		{
			$parts->where('id', 'in', $uids);
		}
		
		if($page_id !== NULL)
		{
			$op = is_array($page_id) ? 'in' : '=';
			if(  is_array( $page_id ))
			{
				$op = 'in';
				$page_id = $this->prepare_param($page_id, array('Valid', 'numeric'));
			}
			else
			{
				$page_id = (int) $page_id;
				$op = '=';
			}

			$parts
				->where('page_id', $op, $page_id);
		}
		
		$parts = $parts
			->execute()
			->as_array();
		
	}
}