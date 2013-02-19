<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi/Api
 */
class Model_API_Page_Part extends Model_API {
	
	protected $_table_name = 'page_parts';
	
	protected $_secured_columns = array(
		'content', 'content_html'
	);
	
	public function get_all($page_id, $fields = array())
	{
		$fields = $this->prepare_param($fields);

		$parts = DB::select('id', 'name')
			->select_array( $this->filtered_fields( $fields ) )
			->from($this->table_name());

		$parts
			->where('page_id', '=', (int) $page_id);
		
		return $parts
			->execute()
			->as_array();
	}
}