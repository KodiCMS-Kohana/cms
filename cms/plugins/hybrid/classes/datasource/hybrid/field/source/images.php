<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Source_Images extends DataSource_Hybrid_Field_Source {

	/**
	 * @var bool
	 */
	protected $_is_searchable = FALSE;

	/**
	 * @var bool
	 */
	protected $_is_indexable = FALSE;

	/**
	 * @var array
	 */
	protected $_props = array(
		'width' => 1024,
		'height' => 1024,
		'crop' => FALSE,
		'master' => Image::AUTO,
		'quality' => 100,
		'max_size' => 1048576,
		'watermark' => FALSE,
		'watermark_path' => NULL,
		'watermark_center' => TRUE,
		'watermark_offset_x' => 0,
		'watermark_offset_y' => 0,
		'watermark_opacity' => 100,
		'types' => 'bmp,gif,jpg,jpeg,png,tif',
	);

	public function booleans()
	{
		return array('crop', 'watermark', 'watermark_center');
	}

	public function default_value()
	{
		return FALSE;
	}
	
	public function module_id()
	{
		return 'field_' . $this->id;
	}

	public function load($ids)
	{
		if (!is_array($ids))
		{
			$ids = explode(',', $ids);
		}

		return ORM::factory('media')
			->where('id', 'in', $ids)
			->find_all()
			->as_array('id', 'filename');
	}

	public function onCreateDocument(DataSource_Hybrid_Document $doc)
	{
		return $this->onUpdateDocument($doc, $doc);
	}

	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new)
	{
		$files = Arr::get($_FILES, $this->name);

		$remove_files = $new->get($this->name . '_remove');
		if (!empty($remove_files))
		{
			ORM::factory('media')->delete_by_ids($remove_files);
		}

		if (empty($files))
		{
			return FALSE;
		}

		$old_files = $old->get($this->name);
		$old_files = empty($old_files) ? array() : explode(',', $old_files);

		$files = $this->_normalize_files($files);

		foreach ($files as $file)
		{
			if (!Upload::not_empty($file))
			{
				continue;
			}

			try
			{
				$uploaded_file = ORM::factory('media')
					->set('module', $this->module_id())
					->upload($file, array('jpg', 'jpeg', 'gif', 'png'), $this->max_size);

				if ($uploaded_file->loaded())
				{
					$this->handle_image($uploaded_file->get_file_path());
					$old_files[] = $uploaded_file->id;
				}
			} 
			catch (Exception $ex)
			{
				continue;
			}
		}

		$new->set($this->name, implode(',', $old_files));

		return TRUE;
	}

	public function onRemoveDocument(DataSource_Hybrid_Document $doc)
	{
		$ids = $doc->get($this->name);

		if (!empty($ids))
		{
			ORM::factory('media')->delete_by_ids(explode(',', $ids));

			$doc->set($this->name, '');
		}
	}

	public function remove()
	{
		$images = ORM::factory('media')
			->where('module', '=', $this->module_id());
		
		foreach ($images->find_all() as $image)
		{
			$image->delete();
		}
		
		return parent::remove();
	}
	
	public function get_type()
	{
		return 'TEXT NOT NULL';
	}
	
	protected function _normalize_files($files)
	{
		$file_ary = array();
		$file_count = count($files['name']);
		$file_keys = array_keys($files);

		for ($i = 0; $i < $file_count; $i++)
		{
			foreach ($file_keys as $key)
			{
				$file_ary[$i][$key] = $files[$key][$i];
			}
		}

		return $file_ary;
	}

	public static function fetch_widget_field($widget, $field, $row, $fid, $recurse)
	{
		return (!empty($row[$fid]) 
			? $field->load($row[$fid]) 
			: array());
	}


	public function handle_image($image)
	{
		$image = Image::factory($image);

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

		if ($this->watermark === TRUE AND $this->get_watermark_path() !== NULL)
		{
			$watermark = Image::factory($this->get_watermark_path());
			if ($this->watermark_center === TRUE)
			{
				$this->watermark_offset_x = NULL;
				$this->watermark_offset_y = NULL;
			}

			$image->watermark($watermark, $this->watermark_offset_x, $this->watermark_offset_y, $this->watermark_opacity);
		}

		return $image->save(NULL, $this->quality);
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
	 * @return string|null
	 */
	public function get_watermark_path()
	{
		$image_path = FileSystem::normalize_path(DOCROOT . $this->watermark_path);
		return $this->is_image($image_path) ? $image_path : NULL;
	}

	/**
	 *
	 * @param string $path
	 * @return boolean
	 */
	public function is_image($path)
	{
		if (!file_exists($path) OR is_dir($path))
		{
			return FALSE;
		}

		$a = getimagesize($path);

		if (!$a)
		{
			return FALSE;
		}

		$image_type = $a[2];

		if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP)))
		{
			return TRUE;
		}

		return FALSE;
	}

}