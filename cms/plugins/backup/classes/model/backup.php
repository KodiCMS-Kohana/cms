<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class Model_Backup {

	public $file = NULL;

	protected function __construct($file = NULL) 
	{
		$this->file = $file;
	}
	
	public static function factory($file = NULL)
	{
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		
		$class = NULL;

		switch ( $ext )
		{
			case 'zip':
				$class = 'Model_Backup_FileSystem';
				break;
			case 'sql':
				$class = 'Model_Backup_Database';
				break;
			default:
				throw new Kohana_Exception('Extension not supported');
		}

		return new $class($file);
	}
	
	abstract public function create();

	abstract public function view();

	abstract public function restore();
}