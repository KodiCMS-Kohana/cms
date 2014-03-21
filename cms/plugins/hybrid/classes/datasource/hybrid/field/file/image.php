<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_File_Image extends DataSource_Hybrid_Field_File_File {
	
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
		'types' => 'bmp,gif,jpg,png,tif',
		'max_size' => 1048576
	);

	/**
	 * 
	 * @param array $data
	 * @return DataSource_Hybrid_Field
	 */
	public function set( array $data )
	{		
		$data['crop'] = !empty($data['crop']) ? TRUE : FALSE;		
		return parent::set( $data );
	}
	
	/**
	 * 
	 * @return \DataSource_Hybrid_Field_File
	 */
	public function set_types($types)
	{
		$types = 'bmp,gif,jpg,png,tif';
		parent::set_types($types);

		return $this;
	}
	
	/**
	 * 
	 * @param integer $width
	 */
	public function set_width( $width )
	{
		$this->width = (int) $width;
		
		if($this->width < 1)
		{
			$this->width = 1;
		}
	}
	
	/**
	 * 
	 * @param integer $height
	 */
	public function set_height( $height )
	{
		$this->height = (int) $height;
		
		if($this->height < 1)
		{
			$this->height = 1;
		}
	}
	
	/**
	 * 
	 * @param integer $quality
	 */
	public function set_quality( $quality )
	{
		$this->quality = (int) $quality;
		
		if($this->quality < 1)
		{
			$this->quality = 1;
		}
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Document $old
	 * @param DataSource_Hybrid_Document $new
	 * @return boolean
	 */
	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new)
	{
		$url = $new->get($this->name . '_url');
		
		if( Valid::url($url) )
		{
			$url = $new->get($this->name . '_url');
			
			list($status, $filename) = Upload::from_url( $url, $this->types, $this->folder());

			
			if($status)
			{
				if(rename(TMPPATH . $filename, $this->folder() . $filename))
				{
					$this->_filepath = $this->folder() . $filename;
					
					$this->onRemoveDocument($old);
					$new->set($this->name, $this->folder . $filename);
				}
				else
				{
					unlink(TMPPATH . $filename);
					$status = FALSE;
				}
			}
		}
		else
		{
			$status = parent::onUpdateDocument($old, $new);
		}
		
		if($status !== TRUE ) return $status;
		
		$image = Image::factory( $this->_filepath );
		$width = (int) $this->width;
		$height = (int) $this->height;
		$crop = (bool) $this->crop;


		if( $width > 0 OR $height > 0)
		{
			$image->resize( $width, $height, $this->master );

			if( $crop === TRUE )
			{
				$image->crop( $width, $height );
			}
		}

		$image->save( NULL, $this->quality);

		return $status;
	}
	
	/**
	 * 
	 * @param Validation $validation
	 * @param DataSource_Hybrid_Document $doc
	 * @return Validation
	 */
	public function onValidateDocument( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		$image = $doc->get($this->name);
		
		if($this->isreq === TRUE AND ! empty($image) )
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
		elseif($this->isreq === TRUE AND $this->_from_url === TRUE )
		{
			$validation->rules( $this->name . '_url', array(
				array('Valid::not_empty')
			) );
		}

		if( $this->_from_url === FALSE AND is_array( $image ))
		{
			$validation->rules( $this->name, array(
				array('Upload::valid'),
				array('Upload::type', array(':value', $this->types)),
				array('Upload::size', array(':value', $this->max_size))
			) );
		}
		else if ($this->_from_url === TRUE )
		{
			$ext = strtolower( pathinfo( $image, PATHINFO_EXTENSION ));
			
			$validation->rules( $this->name . '_url', array(
				array('Valid::url'),
				array('in_array', array($ext, $this->types))
			) );
		}

		return $validation
				->label($this->name . '_url', $this->header)
				->label($this->name, $this->header);
	}

	public function get_similar_fields()
	{
		$fields = DataSource_Hybrid_Field_Factory::get_section_fields($this->ds_id, array('file_image'));

		unset($fields[$this->id]);
		$options = array();

		foreach ($fields as $field)
		{
			$options[$field->id] = $field->name;
		}

		return $options;
	}
}