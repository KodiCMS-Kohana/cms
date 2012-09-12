<?php defined('SYSPATH') or die('No direct access allowed.');

class PagePart extends Record
{
    const TABLE_NAME = 'page_parts';
	
    const PART_NOT_PROTECTED = 0;
	const PART_PROTECTED = 1;
    
    public $name = 'body';
    public $filter_id = '';
    public $page_id = 0;
    public $content = '';
    public $content_html = '';
	public $is_protected = 0;
    
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
			if (Filter::get($this->filter_id))
			{
				$filter_class = Filter::get($this->filter_id);

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
        return self::findAllFrom('PagePart', 'page_id='.(int)$id.' ORDER BY id');
    }
    
    public static function deleteByPageId($page_id)
    {
		$parts = self::findAllFrom('PagePart', 'page_id = ' . $page_id);
		
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

} // end PagePart class