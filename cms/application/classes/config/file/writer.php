<?php defined('SYSPATH') OR die('No direct script access.');

class Config_File_Writer extends Kohana_Config_File_Reader implements Kohana_Config_Writer
{
	public function write($group, $key, $config)
	{
		$array = array();
		$file = APPPATH.'config'.DIRECTORY_SEPARATOR.$group.EXT;
		if(file_exists($file))
		{
			$array = include $file;
		}
		
		$array[$key] = $config;
		
		$array = var_export($array, TRUE);
		$string = "<?php defined('SYSPATH') or die('No direct access allowed.');\n\n// This config file was auto generated at " . date('Y-m-d H:i:s') . "\n";
		$string .= "return " . str_replace('  ', "\t", $array) . ";\n";

		try
		{
			// Write the cache
			return (bool) file_put_contents($file, $string, LOCK_EX);
		}
		catch (Exception $e)
		{
			return FALSE;
		}
	}
}