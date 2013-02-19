<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class ORM extends Kohana_ORM {

	public function list_columns()
	{
		$cache = Cache::instance();
		if ( ($result = $cache->get( 'table_columns_' . $this->_object_name )) !== NULL )
		{
			return $result;
		}

		$cache->set( 'table_columns_' . $this->_object_name, $this->_db->list_columns( $this->table_name() ) );

		// Proxy to database
		return $this->_db->list_columns( $this->table_name() );
	}
	
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

			if ( ! is_dir( $path ) )
			{
				mkdir( $path, 0777, TRUE );
				chmod( $path, 0777 );
			}

			$file = $path . $filename;

			$local_params = array(
				'width' => NULL,
				'height' => NULL,
				'master' => NULL,
				'quality' => 95,
				'resize' => TRUE
			);

			$_params = Arr::merge( $local_params, $_params );

			if ( ! copy( $tmp_file, $file ) )
			{
				continue;
			}

			chmod( $file, 0777 );

			$image = Image::factory( $file );

			if(!empty($_params['width']) AND !empty($_params['height']))
			{
				$image->resize( $_params['width'], $_params['height'], $_params['master'] );
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
}
