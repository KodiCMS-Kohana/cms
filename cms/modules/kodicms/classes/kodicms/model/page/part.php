<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Model
 * @author		ButscHSter
 */
class KodiCMS_Model_Page_Part extends Record
{
    const TABLE_NAME = 'page_parts';
	
    const PART_NOT_PROTECTED = 0;
	const PART_PROTECTED = 1;

	public function defaults()
	{
		return array(
			'name' => 'part',
			'page_id' => 0,
			'is_protected' => self::PART_NOT_PROTECTED
		);
	}
    
    public function beforeSave()
    {
		if (!empty($this->permissions))
		{
			$this->savePermissions($this->permissions);
		}
		
		unset($this->permissions);
		
        // apply filter to save is generated result in the database
        if ( ! empty($this->filter_id))
		{
			if (WYSIWYG::get($this->filter_id))
			{
				$filter_class = WYSIWYG::get($this->filter_id);

				if($filter_class !== FALSE)
				{
					$this->content_html = $filter_class->apply($this->content);
					return TRUE;
				}
			}
		}
		
		$this->content_html = $this->content;
        
        return TRUE;
    }
    
    public static function findByPageId($id)
    {
        return self::findAllFrom('Model_Page_Part', array(
			'where' => array(array('page_id', '=', (int) $id)),
			'order_by' => array(array('id', 'asc'))
		));
    }
    
    public static function deleteByPageId($page_id)
    {
		$parts = self::findAllFrom('Model_Page_Part', array(
			'where' => array(array('page_id', '=', (int) $page_id))));
		
		$result = true;
		
		foreach ($parts as $part)
		{
			if ( !$part->delete())
			{
				$result = FALSE;
			}
		}
		
		return $result;
    }
	
	public function is_protected($roles = array( 'administrator', 'developer' ))
	{
		if(
			$this->is_protected == Model_Page_Part::PART_PROTECTED
		AND
			!AuthUser::hasPermission( $roles )
		)
		{
			return TRUE;
		}
		
		return FALSE;
	}

} // end Model_Page_Part class