<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana Cache MongoDB Driver
 *
 * Requires MongoDB
 *
 * @package    Kohana/Cache
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2009-2012 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_Cache_MongoDB extends Cache implements Cache_Tagging, Cache_GarbageCollect {

	/**
	 * Database client
	 *
	 * @var  MongoClient
	 */
	protected $_client;
	
	/**
	 * Database resource
	 *
	 * @var  MongoDB
	 */
	protected $_database;
	
	/**
	 * Database collection
	 *
	 * @var  MongoCollection
	 */
	protected $_collection;

	/**
	 * Sets up the MongoDB table and
	 * initialises the MongoDB connection
	 *
	 * @param  array  $config  configuration
	 * @throws  Cache_Exception
	 */
	protected function __construct(array $config)
	{
		parent::__construct($config);

		$database = Arr::get($this->_config, 'database', NULL);

		if ($database === NULL)
		{
			throw new Cache_Exception('Database path not available in Kohana Cache configuration');
		}

		$this->_client = new MongoClient('mongodb://'.$this->_config['host'].':' . $this->_config['port']);
		
		$this->_database = $this->_client->{$database};
		$this->_collection = $this->_database->{$this->_config['collection']};
		
		$this->_collection->ensureIndex(array(
			'tags' => 1
		));
		$this->_collection->ensureIndex(array(
			'lifetime' => 1
		));
	}

	/**
	 * Retrieve a value based on an id
	 *
	 * @param   string  $id       id
	 * @param   string  $default  default [Optional] Default value to return if id not found
	 * @return  mixed
	 * @throws  Cache_Exception
	 */
	public function get($id, $default = NULL)
	{
		$doc = $this->_collection->findOne(array('_id' => $id));
        return (!empty($doc) AND !empty($doc['data']) AND !$this->expired($doc)) 
			? unserialize($doc['data']) 
			: $default;
	}

	/**
	 * Set a value based on an id. Optionally add tags.
	 *
	 * @param   string   $id        id
	 * @param   mixed    $data      data
	 * @param   integer  $lifetime  lifetime [Optional]
	 * @return  boolean
	 */
	public function set($id, $data, $lifetime = NULL)
	{
		return (bool) $this->set_with_tags($id, $data, $lifetime);
	}

	/**
	 * Delete a cache entry based on id
	 *
	 * @param   string  $id  id
	 * @return  boolean
	 * @throws  Cache_Exception
	 */
	public function delete($id)
	{
		$query = array('_id' => $id);
		$this->_collection->remove($query);
		
		return TRUE;
	}

	/**
	 * Delete all cache entries
	 *
	 * @return  boolean
	 */
	public function delete_all()
	{
        $this->_collection->remove();
        return TRUE;
	}

	/**
	 * Set a value based on an id. Optionally add tags.
	 *
	 * @param   string   $id        id
	 * @param   mixed    $data      data
	 * @param   integer  $lifetime  lifetime [Optional]
	 * @param   array    $tags      tags [Optional]
	 * @return  boolean
	 * @throws  Cache_Exception
	 */
	public function set_with_tags($id, $data, $lifetime = NULL, array $tags = NULL)
	{
		// Setup lifetime
		if ($lifetime === NULL)
		{
			$lifetime = (0 === Arr::get($this->_config, 'default_expire', NULL)) ? 0 : (Arr::get($this->_config, 'default_expire', Cache::DEFAULT_EXPIRE) + time());
		}
		else
		{
			$lifetime = (0 === $lifetime) ? 0 : ($lifetime + time());
		}
		
		$data = serialize($data);
		
		$doc = array(
			'_id' => $id,
			'data' => $data,
			'tags' => ( ! empty($tags) ) ? $tags : array(),
			'lifetime' => $lifetime
       	);
		
		return (bool) $this->_collection->save($doc);
	}

	/**
	 * Delete cache entries based on a tag
	 *
	 * @param   string  $tag  tag
	 * @return  boolean
	 * @throws  Cache_Exception
	 */
	public function delete_tag($tag)
	{
		$this->_collection->remove(array('tags' => $tag));
		return TRUE;
	}

	/**
	 * Find cache entries based on a tag
	 *
	 * @param   string  $tag  tag
	 * @return  array
	 * @throws  Cache_Exception
	 */
	public function find($tag)
	{
		$result = array();
        $cursor = $this->_collection->find(array(
			'tags' => $tag
		));

        foreach($cursor as $doc) 
		{
			if(!empty($doc) && !empty($doc['data']) && !$this->expired($doc)) 
			{
				$result[$doc['id']] = unserialize($doc['data']);
			}        		
        }

        return $result;
	}

	/**
	 * Garbage collection method that cleans any expired
	 * cache entries from the cache.
	 *
	 * @return  void
	 */
	public function garbage_collect()
	{
        $this->_collection->remove(array('lifetime' => array('$gt' => 0, '$lte' => time())));
        return TRUE;
	}
	
	protected function expired($doc) {
		return ( ! empty($doc['lifetime']) AND ((int)$doc['lifetime']) <= time());
	}
}
