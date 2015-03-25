<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		Kohana/Cache
 * @category	Base
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Cache_File extends Kohana_Cache_File implements Cache_Tagging {

	/**
	 *
	 * @var SplFileInfo 
	 */
	protected $_tags_cache_dir;
	
	/**
	 * 
	 * @param array $config
	 * @throws Cache_Exception
	 */
	protected function __construct(array $config)
	{
		$this->config($config);
		
		$dirs = array('', 'tags');
		
		foreach ($dirs as $dir)
		{
			try
			{
				$directory = Arr::get($this->_config, 'cache_dir', Kohana::$cache_dir);
				$key = '_cache_dir';

				if (!empty($dir))
				{
					$directory .= DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR;
					$key = '_' . $dir . $key;
				}
			
				$splfile = new SplFileInfo($directory);
				
				if (!$splfile->isDir())
				{
					throw new Cache_Exception('Cache directory is not exists: :resource', array(':resource' => $directory));
				}
			}
			catch (Cache_Exception $e)
			{
				$splfile = $this->_make_directory($directory, 0777, TRUE);
			}
			// PHP < 5.3 exception handle
			catch (ErrorException $e)
			{
				$splfile = $this->_make_directory($directory, 0777, TRUE);
			}
			// PHP >= 5.3 exception handle
			catch (UnexpectedValueException $e)
			{
				$splfile = $this->_make_directory($directory, 0777, TRUE);
			}

			// If the defined directory is a file, get outta here
			if ($splfile->isFile())
			{
				throw new Cache_Exception('Unable to create cache directory as a file already exists : :resource', array(':resource' => $splfile->getRealPath()));
			}

			// Check the read status of the directory
			if (!$splfile->isReadable())
			{
				throw new Cache_Exception('Unable to read from the cache directory :resource', array(':resource' => $splfile->getRealPath()));
			}

			// Check the write status of the directory
			if (!$splfile->isWritable())
			{
				throw new Cache_Exception('Unable to write to the cache directory :resource', array(':resource' => $splfile->getRealPath()));
			}

			$this->{$key} = $splfile;
			unset($directory, $splfile);
		}
	}

	/**
	 * 
	 * @param string $id
	 * @param mixed $data
	 * @param integer|null $lifetime
	 * @param array $tags
	 * @return boolean
	 */
	public function set_with_tags($id, $data, $lifetime = NULL, array $tags = NULL)
	{
		if ($this->set($id, $data, $lifetime))
		{
			$this->set_key_to_tags($id, $tags);
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * 
	 * @param string $tag
	 */
	public function delete_tag($tag)
	{
		foreach ($this->find($tag) as $id)
		{
			$this->delete($id);
		}

		if ($this->_exists_tag_file($tag))
		{
			unlink($this->_get_file_by_tag($tag));
		}
	}

	/**
	 * 
	 * @param string $tag
	 */
	public function find($tag)
	{
		return $this->_exists_tag_file($tag) ? file($this->_get_file_by_tag($tag), FILE_IGNORE_NEW_LINES) : array();
	}

	/**
	 * 
	 * @param string $id
	 * @param string $tag
	 * @return boolean
	 */
	public function set_key_to_tag($id, $tag)
	{
		if (!$this->_exists_tag_file($tag))
		{
			touch($this->_get_file_by_tag($tag));
			chmod($this->_get_file_by_tag($tag), 0777);
		}

		return file_put_contents($this->_get_file_by_tag($tag), $id . "\n", FILE_APPEND);
	}

	/**
	 * 
	 * @param string $id
	 * @param array $tags
	 */
	protected function set_key_to_tags($id, array $tags)
	{
		foreach ($tags as $tag)
		{
			$this->set_key_to_tag($id, $tag);
		}
	}

	/**
	 * 
	 * @param string $tag
	 * @return boolean
	 */
	protected function _exists_tag_file($tag)
	{
		return file_exists($this->_get_file_by_tag($tag));
	}

	/**
	 * 
	 * @param string $tag
	 * @return string
	 */
	protected function _get_file_by_tag($tag)
	{
		return $this->_tags_cache_dir . DIRECTORY_SEPARATOR . md5($tag) . '.tag';
	}
}