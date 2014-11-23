<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
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
		'watermark' => FALSE,
		'watermark_path' => NULL,
		'watermark_offset_x' => 0,
		'watermark_offset_y' => 0,
		'watermark_opacity' => 100,
		'types' => 'bmp,gif,jpg,jpeg,png,tif',
		'max_size' => 1048576
	);
	
	public function booleans()
	{
		return array('crop', 'watermark');
	}

	/**
	 * 
	 * @param array $data
	 * @return DataSource_Hybrid_Field
	 */
	public function set(array $data)
	{
		return parent::set($data);
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
	public function set_width($width)
	{
		$this->width = (int) $width;

		if ($this->width < 1)
		{
			$this->width = 1;
		}
	}

	/**
	 * 
	 * @param integer $height
	 */
	public function set_height($height)
	{
		$this->height = (int) $height;

		if ($this->height < 1)
		{
			$this->height = 1;
		}
	}
	
	/**
	 * 
	 * @param integer $quality
	 */
	public function set_quality($quality)
	{
		$this->quality = (int) $quality;

		if ($this->quality < 1)
		{
			$this->quality = 1;
		}
		else if ($this->quality > 100)
		{
			$this->quality = 100;
		}
	}

	/**
	 * 
	 * @param string $path
	 */
	public function set_watermark_path($path)
	{
		$path = FileSystem::normalize_path($path);
		$image_path = DOCROOT . $path;
	
		if ($this->is_image($image_path))
		{
			$this->watermark_path = $path;
		}
		else
		{
			$this->watermark = FALSE;
			$this->watermark_path = NULL;
		}
	}

	/**
	 * 
	 * @param integer $x
	 */
	public function set_watermark_offset_x($x)
	{
		$this->watermark_offset_x = (int) $x;
	}

	/**
	 * 
	 * @param integer $y
	 */
	public function set_watermark_offset_y($y)
	{
		$this->watermark_offset_y = (int) $y;
	}

	/**
	 * 
	 * @param integer $opacity
	 */
	public function set_watermark_opacity($opacity)
	{
		$this->watermark_opacity = (int) $opacity;

		if ($this->watermark_opacity < 1)
		{
			$this->watermark_opacity = 1;
		}
		else if ($this->watermark_opacity > 100)
		{
			$this->watermark_opacity = 100;
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
		$status = FALSE;

		if (Valid::url($url))
		{
			$url = $new->get($this->name . '_url');

			$filename = Upload::from_url($url, $this->folder(), NULL, $this->types);

			if (!empty($filename))
			{
				$this->_filepath = $this->folder() . $filename;
				$this->onRemoveDocument($old);
				$new->set($this->name, $this->folder . $filename);
				$status = TRUE;
			}
		}
		else
		{
			$status = parent::onUpdateDocument($old, $new);
		}

		if ($status !== TRUE)
		{
			return $status;
		}

		$image = Image::factory($this->_filepath);

		$width = (int) $this->width;
		$height = (int) $this->height;
		$crop = (bool) $this->crop;


		if ($width > 0 OR $height > 0)
		{
			$image->resize($width, $height, $this->master);

			if ($crop === TRUE)
			{
				$image->crop($width, $height);
			}
		}

		if ($this->watermark === TRUE AND $this->watermark_path !== NULL)
		{
			$watermark = Image::factory(DOCROOT . $this->watermark_path);
			$image->watermark($watermark, $this->watermark_offset_x, $this->watermark_offset_y, $this->watermark_opacity);
		}

		$image->save(NULL, $this->quality);

		return $status;
	}

	/**
	 * 
	 * @param Validation $validation
	 * @param DataSource_Hybrid_Document $doc
	 * @return Validation
	 */
	public function onValidateDocument(Validation $validation, DataSource_Hybrid_Document $doc)
	{
		$image = $doc->get($this->name);

		$url = $doc->get($this->name . '_url');
		$from_url = FALSE;
		if (Valid::url($url))
		{
			$from_url = TRUE;
		}

		if ($this->isreq === TRUE AND $from_url === FALSE)
		{
			if (is_array($image))
			{
				$validation->rules($this->name, array(
					array('Upload::not_empty')
				));
			}
			else
			{
				$validation->rules($this->name, array(
					array('not_empty')
				));
			}
		}
		elseif ($this->isreq === TRUE AND $from_url === TRUE)
		{
			$validation->rules($this->name . '_url', array(
				array('not_empty')
			));
		}

		if ($from_url === FALSE AND is_array($image))
		{
			$validation->rules($this->name, array(
				array('Upload::valid'),
				array('Upload::type', array(':value', $this->types)),
				array('Upload::size', array(':value', $this->max_size))
			));
		}
		else if ($from_url === TRUE)
		{
			$ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));

			$validation->rules($this->name . '_url', array(
				array('url'),
				array('in_array', array($ext, $this->types))
			));
		}

		return $validation
			->label($this->name . '_url', $this->header)
			->label($this->name, $this->header);
	}

	/**
	 * 
	 * @return array
	 */
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