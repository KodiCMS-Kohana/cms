<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Datasource
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Api_Datasource_Data extends Controller_System_API
{
	public function get_menu()
	{
		$ds_id = (int) $this->param('ds_id', NULL);

		$tree = Datasource_Data_Manager::get_tree();
		$menu = View::factory('datasource/data/menu', array(
			'tree' => $tree,
			'folders' => Datasource_Folder::get_all(),
			'datasource' => DataSource_Section::load($ds_id)
		));
		
		$this->response((string) $menu);
	}
	
	public function post_menu()
	{
		$folder_id = (int) $this->param('folder_id', 0);
		$ds_id = (int) $this->param('ds_id', NULL, TRUE);
		
		$ds = DataSource_Section::load($ds_id);
		
		if ($ds->loaded())
		{
			$ds->move_to_folder($folder_id);
			$this->status = TRUE;
		}
	}
	
	public function get_folder()
	{
		$folder_id = (int) $this->param('id', 0);
		$this->response(DataSource_Folder::get($folder_id));
	}
	
	public function put_folder()
	{
		$name = $this->param('name', NULL, TRUE);

		if (DataSource_Folder::add($name))
		{
			$this->status = TRUE;
		}
	}
	
	public function delete_folder()
	{
		$folder_id = (int) $this->param('id', 0);

		if (DataSource_Folder::delete($folder_id))
		{
			$this->status = TRUE;
		}
	}
}