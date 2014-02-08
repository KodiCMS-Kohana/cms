<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class DataSource_Hybrid_Field_Primitive extends DataSource_Hybrid_Field {
	
	const PRIMITIVE_TYPE_DATE = 'date';
	const PRIMITIVE_TYPE_EMAIL = 'email';
	const PRIMITIVE_TYPE_TIME = 'time';
	const PRIMITIVE_TYPE_DATETIME = 'datetime';
	const PRIMITIVE_TYPE_TEXT = 'text';
	const PRIMITIVE_TYPE_HTML = 'html';
	const PRIMITIVE_TYPE_BOOLEAN = 'boolean';
	const PRIMITIVE_TYPE_INTEGER = 'integer';
	const PRIMITIVE_TYPE_FLOAT = 'float';
	const PRIMITIVE_TYPE_STRING = 'string';
	const PRIMITIVE_TYPE_SLUG = 'slug';
	const PRIMITIVE_TYPE_SELECT = 'select';

	protected $_props = array(
		'default' => NULL,
		'min' => NULL, 
		'max' => NULL,
		'length' => 0,
		'allowed_tags' => '<b><i><u><p><ul><li><ol>',
		'regexp' => NULL,
		'isreq' => FALSE,
		'select' => array(),
		'separator' => '-'
	);
	
	public static function types()
	{
		return array(
			self::PRIMITIVE_TYPE_STRING		=> __('String'),
			self::PRIMITIVE_TYPE_INTEGER	=> __('Integer'),
			self::PRIMITIVE_TYPE_FLOAT		=> __("Float"),
			self::PRIMITIVE_TYPE_BOOLEAN	=> __('Boolean'),
			self::PRIMITIVE_TYPE_DATE		=> __('Date'),
			self::PRIMITIVE_TYPE_TIME		=> __('Time'),
			self::PRIMITIVE_TYPE_DATETIME	=> __('Datetime'),
			self::PRIMITIVE_TYPE_HTML		=> __('HTML'),
			self::PRIMITIVE_TYPE_TEXT		=> __('Text'),
			self::PRIMITIVE_TYPE_EMAIL		=> __('Email'),
			self::PRIMITIVE_TYPE_SLUG		=> __('Slug'),
			self::PRIMITIVE_TYPE_SELECT		=> __('Select')
		);
	}

	public function __construct( array $data )
	{
		$this->family = self::TYPE_PRIMITIVE;
		
		parent::__construct( $data );
	}
	
	public function set( array $data )
	{				
		switch($this->type) 
		{
			case self::PRIMITIVE_TYPE_SLUG:
				if(!isset($data['from_header']))
				{
					$data['from_header'] = FALSE;
				}
				
				if(!isset($data['unique']))
				{
					$data['unique'] = FALSE;
				}
				break;
			case self::PRIMITIVE_TYPE_TEXT:
				if(!isset($data['allow_html']))
				{
					$data['allow_html'] = FALSE;
				}
				break;
			case self::PRIMITIVE_TYPE_HTML:
				if(!isset($data['filter_html']))
				{
					$data['filter_html'] = FALSE;
				}
				break;
			case self::PRIMITIVE_TYPE_SELECT:
				if(isset($data['select']) AND !is_array($data['select']))
				{
					$data['select'] = preg_split('/\\r\\n|\\r|\\n/', $data['select']);
					$data['select'] = array_combine($data['select'], $data['select']);
				}
				break;
		}
		
		return parent::set( $data );
	}
	
	public function __set($key, $value)
	{
		switch ($key)
		{
			case 'length':
				$value = (int) $value;
				break;
			case 'default':
				switch($this->type) 
				{
					case self::PRIMITIVE_TYPE_BOOLEAN:
						$value = (bool) $value; 
						break;

					case self::PRIMITIVE_TYPE_INTEGER:
						$value = (int) $value;
						break;

					case self::PRIMITIVE_TYPE_FLOAT:
						$value = (float) $value;
						break;
				}
				break;
		}
		
		parent::__set($key, $value);
	}

	public function create() 
	{
		if(parent::create())
		{
			$this->update();
		}

		return $this->id;
	}
	
	public function onCreateDocument($doc) 
	{
		$this->onUpdateDocument($doc, $doc);
	}
	
	public function onUpdateDocument($old, $new) 
	{
		switch($this->type) 
		{
			case self::PRIMITIVE_TYPE_DATE: 
			case self::PRIMITIVE_TYPE_DATETIME:
				$new->fields[$this->name] = $this->format_date($new->fields[$this->name]); 
				break;

			case self::PRIMITIVE_TYPE_HTML:
				if($this->filter_html)
					$new->fields[$this->name] = Kses::filter( $new->fields[$this->name], $this->allowed_tags );
				break;
			case self::PRIMITIVE_TYPE_TEXT:
				if( ! $this->allow_html)
					$new->fields[$this->name] = strip_tags( $new->fields[$this->name] ); 
				break;
			case self::PRIMITIVE_TYPE_BOOLEAN:
				$new->fields[$this->name] = $new->fields[$this->name] ? 1 : 0; 
				break;

			case self::PRIMITIVE_TYPE_INTEGER:
				$new->fields[$this->name] = (int) $new->fields[$this->name];
				break;

			case self::PRIMITIVE_TYPE_FLOAT:
					$new->fields[$this->name] = (float) $new->fields[$this->name];
				break;
			
			case self::PRIMITIVE_TYPE_SLUG:
					$new->fields[$this->name] = URL::title($new->fields[$this->name]);
				break;
			
			case self::PRIMITIVE_TYPE_SELECT:
					if( in_array($new->fields[$this->name], (array) $this->select))
						$new->fields[$this->name] = $this->select[$new->fields[$this->name]];
					else if($new->fields[$this->name] == 0)
						$new->fields[$this->name] = '';
					else
						$new->fields[$this->name] = $old->fields[$this->name];
				break;
		}
	}
	
	public function fetch_value($doc) 
	{
		switch($this->type) 
		{
			case self::PRIMITIVE_TYPE_DATE: 
			case self::PRIMITIVE_TYPE_DATETIME:
				$doc->fields[$this->name] = $this->format_date($doc->fields[$this->name]);
				break;
			case self::PRIMITIVE_TYPE_EMAIL:
				$doc->fields[$this->name] = HTML::mailto($doc->fields[$this->name]);
				break;
		}
	}
	
	public function format_date($value, $format = 'Y-m-d') 
	{
		$time = strtotime(!empty($value) ? $value : 'now');
		return $time > 0 
			? date($this->type == self::PRIMITIVE_TYPE_DATE 
				? $format : $format.' H:i:s', $time) 
			: $value;
	}	
	
	public function document_validation_rules( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		switch($this->type) 
		{
			case self::PRIMITIVE_TYPE_DATE: 
			case self::PRIMITIVE_TYPE_DATETIME:
				$validation->rule( $this->name, 'date' );
				break;
			case self::PRIMITIVE_TYPE_EMAIL:
				$validation->rule( $this->name, 'email');
				break;
			case self::PRIMITIVE_TYPE_INTEGER:
				$validation->rule($this->name, 'digit');
				break;
			case self::PRIMITIVE_TYPE_FLOAT:
				$validation->rule($this->name, 'numeric');
				break;
			case self::PRIMITIVE_TYPE_SLUG:
				if(!empty($this->unique))
				{
					$validation->rule($this->name, array($this, 'check_unique'), array(':value', $doc));
				}
				break;
			case self::PRIMITIVE_TYPE_SELECT:
				$validation->rule($this->name, 'in_array', array(':value', array(0) + $this->select));
				break;
		}
		
		if(!empty($this->min) AND !empty($this->max))
		{
			$validation->rule($this->name, 'range', array(':value', $this->min, $this->max));
		}
		
		if(!empty($this->regexp))
		{
			if(  strpos( $this->regexp, '::' ) !== FALSE )
			{
				list($class, $method) = explode('::', $this->regexp);
			}
			else
			{
				$class = 'Valid';
				$method = $this->regexp;
			}
			
			if(method_exists($class, $method))
			{
				$validation->rule($this->name, array($class, $method));
			}
			else
			{
				$validation->rule($this->name, 'regex', array(':value', $this->regexp));
			}
		}
			
		return parent::document_validation_rules($validation, $doc);
	}
	
	public function check_unique($value, $doc) 
	{
		return ! (bool) DB::select($this->name)
			->from($this->ds_table)
			->where($this->name, '=', $value)
			->where('id', '!=', $doc->id)
			->limit(1)
			->execute()
			->count();
	}
	
	public function get_type() 
	{
		switch($this->type) 
		{
			case self::PRIMITIVE_TYPE_BOOLEAN:	return 'TINYINT(1) UNSIGNED NOT NULL';
			case self::PRIMITIVE_TYPE_DATE:		return 'DATE NOT NULL';
			case self::PRIMITIVE_TYPE_TIME:		return 'TIME NOT NULL';
			case self::PRIMITIVE_TYPE_DATETIME:	return 'DATETIME NOT NULL';
			
			case self::PRIMITIVE_TYPE_SELECT:
			case self::PRIMITIVE_TYPE_TEXT:
			case self::PRIMITIVE_TYPE_HTML:		return 'TEXT NOT NULL';
				
			case self::PRIMITIVE_TYPE_FLOAT:	
				if($this->length < 1 OR $this->length > 11)
				{
					$this->length = 10;
				}
				
				return 'FLOAT NOT NULL';
			
			case self::PRIMITIVE_TYPE_INTEGER:
				if($this->length < 1 OR $this->length > 11)
				{
					$this->length = 10;
				}

				return 'INT(' . $this->length . ') UNSIGNED NOT NULL';
			
			case self::PRIMITIVE_TYPE_STRING:
				if($this->length < 1 OR $this->length > 255)
				{
					$this->length = 32;
				}

				return 'VARCHAR ('.$this->length.') NOT NULL';
			case self::PRIMITIVE_TYPE_SLUG:
				return 'VARCHAR (255) NOT NULL';
			
			case self::PRIMITIVE_TYPE_EMAIL:
				return 'VARCHAR (50) NOT NULL';
		}

		return NULL; 
	}
	
	public static function set_doc_field( $widget, $field, $row, $fid, $recurse )
	{
		switch($field['ds_type']) 
		{
			case self::PRIMITIVE_TYPE_BOOLEAN:	
				return (bool) $row[$fid];
			case self::PRIMITIVE_TYPE_INTEGER:
				return (int) $row[$fid];
			default :
				return $row[$fid];
		}
	}
}