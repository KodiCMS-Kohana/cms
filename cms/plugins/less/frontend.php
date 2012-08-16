<?php defined('SYSPATH') or die('No direct access allowed.');

require PLGPATH.'/less/lessc.inc.php';

function less_compile() {
	
	try
	{
		$path = PUBLIC_SYSPATH.DIRECTORY_SEPARATOR.'less'.DIRECTORY_SEPARATOR;
		$files = new Filesystem($path);

		$params = array(
			'newlineChar' => '',
			'indentChar' => '',
		);

		foreach ($files as $file)
		{
			if ($file->isDot() OR $file->isDir() OR $file->getFilenameUTF8() !== 'common.less') continue;

			$pathinfo = pathinfo($file->getFilenameUTF8() );

			if($pathinfo['extension'] == 'less')
			{
				lessc::ccompile( $path.$file->getFilenameUTF8(), PUBLIC_SYSPATH.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$pathinfo['filename'].'.css', $params);
			}
		}
	}
	catch ( Exception $e ) 
	{
		throw new Exception($e);
	}
}

less_compile();