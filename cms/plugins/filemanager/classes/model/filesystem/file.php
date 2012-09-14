<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_FileSystem_File extends SplFileInfo {
	
	public function setContent($content)
	{
		return file_put_contents($this->getRealPath(), $content);
	}
	
	public function getContent()
	{
		return file_get_contents($this->getRealPath());
	}


	public function delete()
	{
		chmod($this->getRealPath(), 0777);
		return unlink($this->getRealPath());
	}
	
	public function getParent()
	{
		return Model_FileSystem::factory($this->getPathInfo());
	}
	
	public function isImage()
	{
		try
		{
			// Get the image information
			return getimagesize($this->getRealPath());
		}
		catch (Exception $e)
		{
			return FALSE;
		}
	}
}