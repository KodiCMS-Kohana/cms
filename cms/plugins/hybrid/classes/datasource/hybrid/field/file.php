<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class DataSource_Hybrid_Field_File extends DataSource_Hybrid_Field {
	
	/**
	 *
	 * @var array 
	 */
	protected $_props = array(
		'width' => 100,
		'height' => 100, 
		'crop' => FALSE,
		'master' => Image::AUTO,
		'quality' => 95,
		'max_size' => 0,
		'types' => 'jpg,png,gif'
	);
	
	/**
	 *
	 * @var string 
	 */
	public $folder = NULL;

	/**
	 * 
	 * @param array $data
	 */
	public function __construct( array $data )
	{
		$this->max_size = Num::bytes('1MiB');
		
		parent::__construct( $data );
		
		$this->family = self::TYPE_FILE;
		$this->type = self::TYPE_FILE;
	}

	/**
	 * 
	 * @param array $data
	 * @return DataSource_Hybrid_Field
	 */
	public function set( array $data )
	{
		if(!isset($data['crop']))
		{
			$data['crop'] = FALSE;
		}
		
		if(empty($data['linked_fields']))
		{
			$data['linked_fields'] = array();
		}
		
		return parent::set( $data );
	}
	
	/**
	 * 
	 * @param integer $width
	 */
	public function set_width( $width )
	{
		$this->width = (int) $width;
	}
	
	/**
	 * 
	 * @param integer $height
	 */
	public function set_height( $height )
	{
		$this->height = (int) $height;
	}
	
	/**
	 * 
	 * @param integer $quality
	 */
	public function set_quality( $quality )
	{
		$this->quality = (int) $quality;
	}
	
	/**
	 * 
	 * @param array $linked_fields
	 */
	public function set_linked_fields( $linked_fields )
	{
		$this->linked_fields = (array) $linked_fields;
	}	
	
	/**
	 * 
	 * @return array
	 */
	public function linked_fields()
	{
		return (array) $this->linked_fields;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function create() 
	{
		if(parent::create())
		{
			if($this->create_folder()) 
			{
				$this->update();
				return $this->id;
			}

			$this->remove_folder();
		}

		return FALSE;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function remove() 
	{
		$this->remove_folder();
		return parent::remove();
	}
	
	/**
	 * 
	 * @param integer $ds_id
	 */
	public function set_ds($ds_id) 
	{
		parent::set_ds($ds_id);
		
		if($this->ds_id)
		{
			$this->folder = 'hybrid' . DIRECTORY_SEPARATOR . $this->ds_id . DIRECTORY_SEPARATOR . substr($this->name, 2) . DIRECTORY_SEPARATOR;
		}
	}

	/**
	 * 
	 * @return string
	 */
	public function get_type()
	{
		return 'VARCHAR(64)';
	}
	
	/**
	 * 
	 * @param array $types
	 * @return \DataSource_Hybrid_Field_File
	 */
	public function set_types($types) 
	{
		$this->types = array();

		if( ! is_array( $types ) )
		{
			$types = explode(',', $types);
		}

		foreach($types as $i => $type)
		{
			$type = trim($type);
			if( empty($type) OR ! preg_match('~^[A-Za-z0-9_\\-]+$~', $type) OR ! $this->check_disallowed($type))
			{
				unset($types[$i]);
			}
		}
		
		$this->types = $types;
		
		return $this;
	}
	
	/**
	 * 
	 * @param string $file_type
	 * @return boolean
	 */
	protected function check_disallowed($file_type)
	{
		$disallowed = explode(',', '/^php/,/^phtm/,py,pl,/^asp/,htaccess,cgi,_wc,/^shtm/,/^jsp/');
		foreach($disallowed as $type)
		{
			if(
				(
					(strpos($type, '/') !== FALSE) 
				AND 
					preg_match($type, $file_type)
				)
				OR $type == $file_type
			) 
			{
				return FALSE;
			}
				
		}
		
		return TRUE;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function create_folder() 
	{
		if( ! empty($this->folder) AND $this->ds_id AND !file_exists(PUBLICPATH . $this->folder) ) 
		{
			if(mkdir(PUBLICPATH . $this->folder, 0777, TRUE))
			{
				return TRUE;
			}
		}

		return FALSE;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function remove_folder() {
		
		$folder = $this->folder;
		if( ! empty($this->folder) AND is_dir(PUBLICPATH . $this->folder)) 
		{
			FileSystem::factory(PUBLICPATH . $this->folder)->delete();
			return TRUE;
		}

		return !is_dir(PUBLICPATH . $this->folder);
	}
	
	/**
	 * 
	 * @param string $path
	 * @return boolean
	 */
	public function is_image( $path )
	{
		if(!file_exists( $path ) OR is_dir( $path )) return FALSE;

		$a = getimagesize($path);
		$image_type = $a[2];

		if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
		{
			return TRUE;
		}

		return FALSE;
	}
	
	/**
	 * 
	 * @param array $data
	 * @return DataSource_Hybrid_Field
	 */
	public function set_value(array $data, $doc)
	{
		$file = Arr::get($data, $this->name, array());
		
		
		if(is_array($file) AND Upload::valid($file) AND Upload::not_empty($file))
		{
			$data[$this->name] = $this->_move_file($file);
			
			
			
			$related_fields = $this->linked_fields();
			

			if( ! empty($related_fields) )
			{
				foreach($related_fields as $id)
				{
					$related_field = DataSource_Hybrid_Field_Factory::get_field($id);
					if($related_field === NULL) continue;
					
					$doc->fields[$related_field->name] = $related_field->copy_file($data[$this->name]);
				}
			}
		}
		else if(Valid::url( Arr::get($data, $this->name . '_url') )  )
		{
			$data[$this->name] = $data[$this->name . '_url'];
		}
		
		return parent::set_value($data, $doc);
	}
	
	protected function _move_file($file)
	{
		$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		$filename = uniqid() . '.' . $ext;
		$filepath = Upload::save($file, $filename, $this->folder());
		
		return $filepath;
	}
	
	public function copy_file($filepath)
	{
		$ext = strtolower( pathinfo( $filepath, PATHINFO_EXTENSION ) );
		$filename = uniqid() . '.' . $ext;
		$new_filepath = $this->folder() . $filename;
		copy($filepath, $new_filepath);
		
		return $new_filepath;
	}

	/**
	 * 
	 * @param DataSource_Hybrid_Document $doc
	 */
	public function onCreateDocument($doc) 
	{
		$this->onUpdateDocument(NULL, $doc);
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $old
	 * @param DataSource_Hybrid_Document $new
	 * @return boolean
	 */
	public function onUpdateDocument($old, $new)
	{
		$new_file = $new->fields[$this->name];
		
		if(empty($new_file)) 
		{
			$this->set_old_value($old, $new);
			return FALSE;
		}
		elseif( $new_file == -1)
		{
			$this->onRemoveDocument($old);
			
			$new->fields[$this->name] = '';
			return FALSE;
		}
		elseif($old !== NULL AND $new_file == $old->fields[$this->name])
		{
			return FALSE;
		}

		$filepath = NULL;
		
		if( ! empty($new_file) AND strpos($new_file, $this->folder()) !== FALSE)
		{
			$filepath = $new_file;
			$filename = pathinfo($filepath, PATHINFO_BASENAME);
		}
		if( is_string($new_file) AND Valid::url($new_file) )
		{
			list($status, $filename) = Upload::from_url( $new_file, $this->types, $this->folder());

			if($status)
			{
				if(rename(TMPPATH . $filename, $this->folder() . $filename))
				{
					$filepath = $this->folder() . $filename;
				}
				else
				{
					unlink(TMPPATH . $filename);
					return FALSE;
				}
			}
		}

		if( empty($filepath) ) 
		{
			$this->set_old_value($old, $new);
			return FALSE;
		}

		$this->onRemoveDocument($old);
		
		if($this->is_image( $filepath ))
		{
			$image = Image::factory( $filepath );
			
			if(!empty($this->width) OR !empty($this->height))
			{
				$image->resize( $this->width, $this->height, $this->master );

				if(!empty($this->crop ) AND $this->width > 0 AND $this->height > 0)
				{
					$image->crop( $this->width, $this->height );
				}
			}

			$image->save(NULL, $this->quality);
		}
		
		$new->fields[$this->name] = $this->folder . $filename;

		return TRUE;
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $doc
	 */
	public function onRemoveDocument($doc) 
	{
		if( $doc !== NULL AND !empty($doc->fields[$this->name])) 
		{
			@unlink(PUBLICPATH . $doc->fields[$this->name]);
			$doc->fields[$this->name] = '';
		}
	}
	
	/**
	 * 
	 * @param Validation $validation
	 * @param DataSource_Hybrid_Document $doc
	 * @return Validation
	 */
	public function document_validation_rules( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		$image_url = NULL;
		$image = NULL;
		
		if($validation->offsetExists($this->name))
		{
			$image = $validation->offsetGet($this->name );
		}
		
		if($validation->offsetExists($this->name . '_url'))
		{
			$image_url = $validation->offsetGet($this->name . '_url');
		}
		
		if($this->isreq === TRUE AND ! empty($image))
		{
			if(is_array($image))
			{
				$validation->rules( $this->name, array(
					array('Upload::not_empty')
				) );
			}
			else
			{
				$validation->rules( $this->name, array(
					array('Valid::not_empty')
				) );
			}
			
		}
		elseif($this->isreq === TRUE AND !empty($image_url))
		{
			$validation->rules( $this->name . '_url', array(
				array('Valid::not_empty')
			) );
		}

		if(empty($image_url) AND is_array( $image ))
		{
			$validation->rules( $this->name, array(
				array('Upload::valid'),
				array('Upload::type', array(':value', $this->types)),
				array('Upload::size', array(':value', $this->max_size))
			) );
		}
		else
		{
			$ext = strtolower( pathinfo( $image_url, PATHINFO_EXTENSION ));
			
			$validation->rules( $this->name . '_url', array(
				array('Valid::url'),
				array('in_array', array($ext, $this->types))
			) );
		}

		return $validation
				->label($this->name . '_url', $this->header)
				->label($this->name, $this->header);
	}
	
	/**
	 * 
	 * @return string
	 */
	public function folder()
	{
		return PUBLICPATH . $this->folder;
	}

	/**
	 * @param Model_Widget_Hybrid
	 * @param array $field
	 * @param array $row
	 * @param string $fid
	 * @return mixed
	 */
	public static function set_doc_field( $widget, $field, $row, $fid )
	{
		return ! empty($row[$fid]) 
			? str_replace(array('/', '\\'), '/', $row[$fid])
			: NULL;
	}
	
	public function get_similar_fields()
	{
		$fields = DataSource_Hybrid_Field_Factory::get_related_fields($this->ds_id, self::TYPE_FILE);
		unset($fields[$this->id]);

		foreach ($fields as $field)
		{
			$options[$field->id] = $field->name;
		}

		return $options;
	}
}