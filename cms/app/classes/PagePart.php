<?php defined('SYSPATH') or die('No direct access allowed.');

class PagePart extends Record
{
    const TABLE_NAME = 'page_part';
	
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
			$this->savePermissions($this->permissions);
		
		unset($this->permissions);
		
        // apply filter to save is generated result in the database
        if ( ! empty($this->filter_id))
		{
			if (Filter::get($this->filter_id))
				$this->content_html = Filter::get($this->filter_id)->apply($this->content);
			
			foreach(Observer::getObserverList('filter_content') as $callback)
				$this->content_html = call_user_func($callback, $this->content_html);
		}
        else
            $this->content_html = $this->content;
        
        return true;
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
				$result = FALSE;
		}
		
		return $result;
    }

} // end PagePart class