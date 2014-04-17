<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Model
 * @author		ButscHSter
 */
class KodiCMS_Model_Media extends ORM
{
	protected $_serialize_columns = array('params');
	
	public function labels()
	{
		return array(
			'module'			=> __('Related module'),
			'size'				=> __('File size'),
			'content_type'		=> __('Content type'),
			'filename'			=> __('File name'),
			'description'		=> __('Description')
		);
	}

	public function filters()
	{
		return array(
			'size' => array(
				array('intval')
			),
			'module' => array(
				array('trim'),
				array('URL::title', array(':value', '_'))
			),
			'filename' => array(
				array('trim')
			),
			'description' => array(
				array('trim'),
				array('strip_tags')
			)
		);
	}
	
	public function form_columns()
	{
		return array(
			'id' => array(
				'type' => 'input',
				'editable' => FALSE,
				'length' => 10
			),
			'filename' => array(
				'type' => 'input',
				'length' => 255
			),
			'module' => array(
				'type' => 'input',
				'length' => 100
			),
			'description' => array(
				'type' => 'textarea'
			),
		);
	}

	public function upload(array $file, array $types = array(), $max_size = NULL)
	{
		$filename = Upload::file($file, $types, $max_size);		
		$tmp_file = TMPPATH . trim( $filename );
		
		if ( ! file_exists( $tmp_file ) OR is_dir( $tmp_file ))
		{
			throw new Kohana_Exception('Tempory file not exists :file', 
					array(':file' => $tmp_file));
		}
		
		$path =  'media' . DIRECTORY_SEPARATOR . substr($filename, 0, 3) . DIRECTORY_SEPARATOR;
		$abs_path = PUBLICPATH . $path;
		
		if ( ! is_dir( $abs_path ) )
		{
			mkdir( $abs_path, 0777, TRUE );
			chmod( $abs_path, 0777 );
		}
		
		$file = $abs_path . $filename;
		
		if ( ! copy( $tmp_file, $file ) )
		{
			throw new Kohana_Exception("Can't copy file :file", 
					array(':file' => $tmp_file));
		}
		
		chmod( $file, 0777 );
		unlink( $tmp_file );
		
		try
		{
			$content_type = 'image';
			$params = getimagesize($file);
		} 
		catch (Exception $ex) 
		{
			$content_type = 'file';
			$params = array();
		}
		
		return $this
			->set('size', filesize($abs_path))
			->set('content_type', $content_type)
			->set('filename', str_replace(array('/', '\\'), '/', $path) . $filename)
			->set('params', $params)
			->save();
	}
	
	public function delete()
	{
		if ( ! $this->loaded() )
		{
			throw new Kohana_Exception('Media not loaded');
		}
		
		$this->_unlink();
		
		return parent::delete();
	}
	
	protected function _unlink()
	{
		if(file_exists(PUBLICPATH . $this->filename))
		{
			unlink(PUBLICPATH . $this->filename);
		}
		
		return $this;
	}
	
	public function delete_by_ids( array $ids, $module = NULL )
	{
		foreach ($ids as $id)
		{
			$media = ORM::factory('media')
				->where('id', '=', $id);
			
			if($module !== NULL)
			{
				$media->where('module', '=', $module);
			}

			$media->find()->delete();
		}
		
		return $this;
	}
}