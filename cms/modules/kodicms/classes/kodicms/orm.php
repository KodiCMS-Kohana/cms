<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class KodiCMS_ORM extends Kohana_ORM {
	
	/**
	 * Enables the query to be cached for a specified amount of time.
	 *
	 * @param   integer  $lifetime  number of seconds to cache
	 * @return  $this
	 * @uses    Kohana::$cache_life
	 */
	public function cache_tags( array $tags )
	{
		// Add pending database call which is executed after query type is determined
		$this->_db_pending[] = array(
			'name' => 'cache_tags',
			'args' => array( $tags ),
		);

		return $this;
	}

	/**
	 * 
	 * @return array
	 */
	public function list_columns()
	{
		if(Kohana::$caching === TRUE)
		{
			$cache = Cache::instance();
			if ( ($result = $cache->get( 'table_columns_' . $this->_object_name )) !== NULL )
			{
				return $result;
			}

			$cache->set( 'table_columns_' . $this->_object_name, $this->_db->list_columns( $this->table_name() ) );
		}

		// Proxy to database
		return $this->_db->list_columns( $this->table_name() );
	}
	
	/**
	 * 
	 * @param string $file
	 * @param string $field
	 * @param array $params
	 * @return null|string
	 * @throws Kohana_Exception
	 */
	public function add_image( $file, $field = NULL, $params = NULL )
	{
		if ( $field !== NULL AND ! $this->loaded() )
		{
			throw new Kohana_Exception( 'Model must be loaded' );
		}

		if ( $params === NULL )
		{
			$params = $this->images();
		}

		$tmp_file = TMPPATH . trim( $file );

		if ( ! file_exists( $tmp_file ) OR is_dir( $tmp_file ))
		{
			return NULL;
		}

		$ext = strtolower( pathinfo( $tmp_file, PATHINFO_EXTENSION ) );
		$filename = uniqid() . '.' . $ext;
		
		foreach ( $params as $path => $_params )
		{
			$path = PUBLICPATH . trim( $path, '/' ) . DIRECTORY_SEPARATOR;

			$local_params = array(
				'width' => NULL,
				'height' => NULL,
				'master' => NULL,
				'quality' => 95,
				'crop' => TRUE
			);

			$_params = Arr::merge( $local_params, $_params );
			
			if( !empty($_params['subfolder']) )
			{
				$path .= trim($_params['subfolder']) . DIRECTORY_SEPARATOR;
			}
			
			$path = FileSystem::normalize_path($path);
			
			if ( ! is_dir( $path ) )
			{
				mkdir( $path, 0777, TRUE );
				chmod( $path, 0777 );
			}

			$file = $path . $filename;

			if ( ! copy( $tmp_file, $file ) )
			{
				continue;
			}

			chmod( $file, 0777 );

			$image = Image::factory( $file );

			if(!empty($_params['width']) AND !empty($_params['height']))
			{
				if($_params['width'] < $image->width OR $_params['height'] < $image->height )
					$image->resize( $_params['width'], $_params['height'], $_params['master'] );

				if($_params['crop'])
					$image->crop( $_params['width'], $_params['height'] );
			}

			$image->save();
		}

		if ( $field !== NULL )
		{
			$this
				->set($field, $filename)
				->update();
		}
			
		unlink( $tmp_file );

		return $filename;
	}
	
	/**
	 * 
	 * @param type $field
	 * @return \ORM
	 * @throws Kohana_Exception
	 */
	public function delete_image( $field )
	{
		if ( ! $this->loaded() )
		{
			throw new Kohana_Exception( 'Model must be loaded' );
		}

		foreach ($this->images() as $path => $data)
		{
			$file = PUBLICPATH . $path . DIRECTORY_SEPARATOR . $this->get($field);
			if(file_exists($file) AND !is_dir($file))
			{
				unlink($file);
			}
		}

		$this
			->set($field, '')
			->update();
		
		return $this;
	}
	
	public function get_related_ids( $alias )
	{
		if( ! isset($this->_has_many[$alias]))
		{
			throw new Kohana_Exception('Relation :alias not exists in object :object', array(
				':alias' => $alias,
				':object' => $this->object_name()
			));
		}

		if( ! $this->loaded() )
		{
			return array();
		}

		$table_name = $this->_has_many[$alias]['through'];
		$filed = $this->_has_many[$alias]['foreign_key'];
		$related_field = $this->_has_many[$alias]['far_key'];

		return DB::select($related_field)
			->from( $table_name )
			->where($filed, '=', $this->pk())
			->execute($this->_db)
			->as_array( NULL, $related_field);
	}

	public function update_related_ids( $alias, $new_ids = array(), $current_ids = array() )
	{
		if( ! is_array($new_ids) )
		{
			return $this;
		}

		if ( ! $this->loaded() AND ! empty( $new_ids ) )
		{
			return $this->add( $alias, $new_ids );
		}
		
		if( empty( $current_ids ) )
		{
			$current_ids = $this->get_related_ids( $alias );
		}

		$old_ids = array_diff( $current_ids, $new_ids );
		$new_ids = array_diff( $new_ids, $current_ids );

		if ( !empty( $old_ids ) )
		{
			$this->remove( $alias, $old_ids );
		}

		if ( !empty( $new_ids ) )
		{
			$this->add( $alias, $new_ids );
		}

		return $this;
	}
}
