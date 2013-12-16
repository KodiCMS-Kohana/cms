<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/API
 * @category	Model
 * @author		ButscHSter
 */
class Model_Api_key extends ORM {
	
	protected $_created_column = array(
		'column' => 'created_on',
		'format' => 'Y-m-d H:i:s'
	);
	
	/**
	 * 
	 * @param type $description
	 */
	public function generate( $description = '' )
	{
		$this->values(array(
				'id' => self::generate_key(),
				'description' => $description
			), array('id', 'description'))
			->create();

		return $this->id;
	}
	
	public function refresh( $old_key )
	{
		$this->where('id', '=', $old_key)->find();
		
		if( ! $this->loaded()) return FALSE;
		
		$this->values(array(
			'id' => self::generate_key()
		), array('id'))
			->update();

		return $this->id;
	}
	
	/**
	 * 
	 * @param string $key
	 * @return bool
	 */
	public function is_valid($key)
	{
		return $this
			->reset()
			->where('id', '=', HTML::chars($key))
			->find()
			->loaded();
	}

	/**
	 * 
	 * @return string
	 */
	public static function generate_key()
	{
		$microTime = microtime();
		list($a_dec, $a_sec) = explode(' ', $microTime);

		$dec_hex = dechex($a_dec * 1000000);
		$sec_hex = dechex($a_sec);

		$dec_hex = self::_ensure_length($dec_hex, 5);
		$sec_hex = self::_ensure_length($sec_hex, 6);

		$guid = '';
		$guid .= $dec_hex;
		$guid .= self::_create_guid_section(3);
		$guid .= '-';
		$guid .= self::_create_guid_section(4);
		$guid .= '-';
		$guid .= self::_create_guid_section(4);
		$guid .= '-';
		$guid .= self::_create_guid_section(4);
		$guid .= '-';
		$guid .= $sec_hex;
		$guid .= self::_create_guid_section(6);

		return $guid;
	}
	
	/**
	 * 
	 * @param integer $characters
	 * @return striing
	 */
	private static function _create_guid_section( $characters )
	{
		$characters = (int) $characters;
		$return = '';

		for($i=0; $i < $characters; $i++)
		{
			$return .= dechex(mt_rand(0,15));
		}

		return $return;
	}

	/**
	 * 
	 * @param string $string
	 * @param integer $length
	 * @return type
	 */
	private static function _ensure_length( $string, $length)
	{
		$length = (int) $length;
		$strlen = strlen($string);

		if($strlen < $length)
		{
			$string = str_pad($string, $length, 0);
		}
		else if($strlen > $length)
		{
			$string = substr($string, 0, $length);
		}
		
		return $string;
	}
	
}