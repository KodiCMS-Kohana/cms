<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Model
 * @author		ButscHSter
 */
class KodiCMS_Model_File_Layout extends Model_File {
	
	protected $_folder = 'layouts';
	
	/**
	 *
	 * @var array 
	 */
	protected $_blocks = NULL;
	
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
		return DB::select(array(DB::expr('COUNT(*)'), 'total'))
			->from('pages')
			->where('layout_file', '=', $this->name)
			->execute()
			->get('total');
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
		
		Cache::instance()->delete_tag('layout_blocks');
		
		return $this;
	}
}