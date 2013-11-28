<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Cache
 * @author		ButscHSter
 */
class KodiCMS_Cache_Apc extends Kohana_Cache_Apc implements Cache_Tagging {

	public function set_with_tags($id, $data, $lifetime = NULL, array $tags = NULL)
	{
		if($this->set($id, $data, $lifetime))
		{
			$this->set_key_to_tags($id, $tags);
			return TRUE;
		}
		
		return FALSE;
	}

	public function delete_tag($tag)
	{
		$tag_key = "internal_tags:tag:{$tag}";
		foreach ($this->find($tag_key) as $stored_key => $nothing) 
		{
			$this->remove($stored_key);
		}

        $this->remove($tag_key);
	}
	
	public function set_key_to_tag($id, $tag)
	{
		$tag_key = "internal_tags:tag:{$tag}";
		$tag_stored_keys = $this->find($tag_key);

		if ( ! isset($tag_stored_keys[$id]) ) 
		{
			$tag_stored_keys[$id] = 1;
		}

		$this->set($tag_key, $tag_stored_keys);
	}
	
	public function set_key_to_tags($id, $tags)
	{
		if (empty($tags)) return;
		
        foreach ($tags as $tag) 
		{
            $this->set_key_to_tag($id, $tag);
        }
	}

	public function find($tag_key)
	{
		$tag_stored_keys = $this->get($tag_key, array());
        
		if ( ! is_array($tag_stored_keys) ) 
		{
            $tag_stored_keys = array();
        }
		
		return $tag_stored_keys;
	}
} 
