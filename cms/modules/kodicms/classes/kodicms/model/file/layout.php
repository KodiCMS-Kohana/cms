<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Model
 * @author		ButscHSter
 */
class KodiCMS_Model_File_Layout extends Model_File {
	
	/**
	 *
	 * @var array 
	 */
	protected $_blocks = NULL;

	public function __construct( $name = '' )
	{
		$this->_path = LAYOUTS_SYSPATH;
		parent::__construct( $name );
	}
	
	public function blocks()
	{
		if($this->_blocks === NULL)
		{
			$this->_blocks = ORM::factory('layout_block')
				->find_by_layout($this->name);
		}
		
		return $this->_blocks;
	}

	/**
	 * 
	 * @return integer
	 */
	public function is_used()
    {
        return Record::countFrom('Model_Page', array('where' => array(array('layout_file', '=', ':name'))), array(
			':name' => $this->name
		));
    }
	
	/**
	 * 
	 * @return boolean
	 */
	public function save()
	{
		$result = parent::save();
		
		$this->rebuild_blocks();
		
		return $result;
	}
	
	public function rebuild_blocks()
	{
		$blocks = Block::parse_content($this->content);
		
		DB::delete('layout_blocks')
			->where('layout_name', '=', $this->name)
			->execute();
		
		if( !empty($blocks)) 
		{
			$insert = DB::insert('layout_blocks')
				->columns(array(
					'position', 'block', 'layout_name'
				));
			
			foreach ($blocks as $position => $block)
			{
				$insert->values(array(
					$position, $block, $this->name
				));
			}
			
			$insert->execute();
		}
		
		return $this;
	}
}