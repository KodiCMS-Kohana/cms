<?php defined('SYSPATH') or die('No direct script access.');

class KodiCMS_Cache_File extends Kohana_Cache_File implements Cache_Tagging {
	
	/**
	 * Creates a hashed filename based on the string. This is used
	 * to create shorter unique IDs for each cache filename.
	 *
	 *     // Create the cache filename
	 *     $filename = Cache_File::filename($this->_sanitize_id($id));
	 *
	 * @param   string  $string  string to hash into filename
	 * @return  string
	 */
	protected static function filename($string)
	{
		return sha1($string).'.txt';
	}
	
	/**
	 * @var  string   the tags caching directory
	 */
	protected $_tags_cache_dir;
	
	protected function __construct(array $config)
	{
		// Setup parent
		parent::__construct($config);
		
		$directory = Arr::get($this->_config, 'cache_dir', Kohana::$cache_dir) . DIRECTORY_SEPARATOR . 'tags';
		
		try
		{
			$this->_tags_cache_dir = new SplFileInfo($directory);
		}
		// PHP < 5.3 exception handle
		catch (ErrorException $e)
		{
			$this->_tags_cache_dir = $this->_make_directory($directory, 0777, TRUE);
		}
		// PHP >= 5.3 exception handle
		catch (UnexpectedValueException $e)
		{
			$this->_tags_cache_dir = $this->_make_directory($directory, 0777, TRUE);
		}
		
		// If the defined directory is a file, get outta here
		if ($this->_tags_cache_dir->isFile())
		{
			throw new Cache_Exception('Unable to create cache directory as a file already exists : :resource', array(':resource' => $directory));
		}

		// Check the read status of the directory
		if ( ! $this->_tags_cache_dir->isReadable())
		{
			throw new Cache_Exception('Unable to read from the cache directory :resource', array(':resource' => $directory));
		}

		// Check the write status of the directory
		if ( ! $this->_tags_cache_dir->isWritable())
		{
			throw new Cache_Exception('Unable to write to the cache directory :resource', array(':resource' => $directory));
		}
		
	}
	
	/**
	 * Set a value based on an id. Optionally add tags.
	 *
	 * Note : Some caching engines do not support
	 * tagging
	 *
	 * @param   string   $id        id
	 * @param   mixed    $data      data
	 * @param   integer  $lifetime  lifetime [Optional]
	 * @param   array    $tags      tags [Optional]
	 * @return  boolean
	 */
	public function set_with_tags($id, $data, $lifetime = NULL, array $tags = NULL)
	{
		if($this->set($id, $data, $lifetime))
		{
			$this->set_key_to_tags($id, $tags);
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Delete cache entries based on a tag
	 *
	 * @param   string  $tag  tag
	 */
	public function delete_tag($tag)
	{
		foreach ($this->find($tag) as $id)
		{
			$this->delete($id);
		}
		
		if($this->_exists_tag_file( $tag ))
			unlink($this->_get_file_by_tag($tag));
	}

	/**
	 * Find cache entries based on a tag
	 *
	 * @param   string  $tag  tag
	 * @return  array
	 */
	public function find($tag)
	{
		return $this->_exists_tag_file( $tag ) ? file($this->_get_file_by_tag($tag), FILE_IGNORE_NEW_LINES) : array();
	}
	
	public function set_key_to_tag($id, $tag)
	{
		if ( ! $this->_exists_tag_file( $tag ))
		{
			touch($this->_get_file_by_tag($tag));
			chmod($this->_get_file_by_tag($tag), 0777);
		}		
		
		return file_put_contents($this->_get_file_by_tag($tag), $id . "\n", FILE_APPEND);
	}

	protected function set_key_to_tags($id, array $tags)
	{
		foreach ($tags as $tag)
		{
			$this->set_key_to_tag($id, $tag);
		}
	}
	
	protected function _exists_tag_file($tag)
	{
		return file_exists( $this->_get_file_by_tag($tag));
	}

	protected function _get_file_by_tag($tag)
	{
		return $this->_tags_cache_dir . DIRECTORY_SEPARATOR . $tag . '.txt';
	}
}