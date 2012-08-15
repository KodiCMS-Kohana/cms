<?php if(!defined('CMS_ROOT')) die;

/**
 * Flexo CMS - Content Management System. <http://flexo.up.dn.ua>
 * Copyright (C) 2008 Maslakov Alexander <jmas.ukraine@gmail.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Flexo CMS.
 *
 * Flexo CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Flexo CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Flexo CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Flexo CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * @package Flexo
 * @subpackage plugins.file_manager
 *
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

class FileManagerController extends PluginController
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setLayout('backend');
	}
	
	public function index()
	{		
		$args = array();
		
		foreach (func_get_args() as $arg)
		{
			if ($arg != '.' && $arg != '..')
				$args[] = urldecode($arg);
		}
		
		$path = PUBLIC_ROOT.DIRECTORY_SEPARATOR. join($args, DIRECTORY_SEPARATOR);
		
		use_helper('Filesystem');
		
		try
		{
			$files = new Filesystem($path);
		}
		catch(Exception $e)
		{
			Flash::set('error', __('Directory not found!'));
			redirect(get_url('plugin/file_manager'));
		}
		
		$this->display('file_manager/views/index', array(
			'files' => $files,
			'now_path' => join($args, '/')
		));
	}
	
	public function documentation()
	{
		echo 'file_manager docs';
	}
	
	public function settings()
	{
		echo 'file_manager settings';
	}
	
	public function dialog()
	{		
		echo new View('../../'.PLUGINS_DIR_NAME.'/file_manager/views/dialog');
	}
	
	public function files_json()
	{
		$args = array();
		
		foreach (func_get_args() as $arg)
		{
			if ($arg != '.' && $arg != '..')
				$args[] = urldecode($arg);
		}
		
		$path = PUBLIC_ROOT.DIRECTORY_SEPARATOR. join($args, DIRECTORY_SEPARATOR);
		
		use_helper('Filesystem');
		
		try
		{
			$files = new Filesystem($path);
		}
		catch(Exception $e)
		{
			echo json_encode(array('error' => __('Directory not found!')));
		}
		
		$result = array();
		
		foreach ($files as $file)
		{
			if ($file->isDot()) continue;
			
			$result[] = array(
				'is_dir' => $file->isDir(),
				'name'   => $file->getFilenameUTF8(),
				'size'   => convert_size($file->getSize()),
				'icon'   => (!$file->isDir() && file_exists(PLUGINS_ROOT.'/file_manager/images/files/file-'.$file->getExt().'.png') ? 'file-'.$file->getExt().'.png': null)
			);
		}
		
		echo json_encode($result);
	}
	
	public function upload()
	{
		require_once('fileuploader/qqUploader.php');
		
		$folder_name = urldecode(str_replace('..', '', $_GET['folder']));
		
		if (PHP_OS == 'WIN' || PHP_OS == 'WINNT')
			$folder_name = iconv('UTF-8', 'CP1251', $folder_name);
		
		$folder_path = PUBLIC_ROOT.DIRECTORY_SEPARATOR.$folder_name.DIRECTORY_SEPARATOR;
		
		if (is_dir($folder_path))
		{
			// list of valid extensions, ex. array("jpeg", "xml", "bmp")
			$allowedExtensions = array();
			// max file size in bytes
			//$sizeLimit = 10 * 1024 * 1024;
			
			$uploader = new qqFileUploader($allowedExtensions);
			$result = $uploader->handleUpload($folder_path, true);
			
			// to pass data through iframe you will need to encode all html tags
			echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		}
		else
		{
			echo json_encode(array('error' => 'Folder \''.$folder_name.'\' not valid!'));
		}
	}
	
	public function create_folder()
	{
		$args = array();
		
		foreach (func_get_args() as $arg)
		{
			if ($arg != '.' && $arg != '..')
				$args[] = urldecode($arg);
		}
		
		$folder_path = PUBLIC_ROOT.DIRECTORY_SEPARATOR. join($args, DIRECTORY_SEPARATOR);
		
		if (PHP_OS == 'WIN' || PHP_OS == 'WINNT')
			$folder_path = iconv('UTF-8', 'CP1251', $folder_path);
		
		if ( ! is_dir($folder_path))
		{
			if (mkdir($folder_path))
			{
				Flash::set('success', __('Directory successfuly created!'));
				redirect(get_url('plugin/file_manager/'.PUBLIC_DIR_NAME.'/'.join($args, '/')));
			}
			else
			{
				Flash::set('error', __('Directory not created!'));
				redirect(get_url('plugin/file_manager'));
			}
		}
		else
		{
			Flash::set('error', __('Directory already exists!'));
			redirect(get_url('plugin/file_manager'));
		}
	}
	
	public function create_folder_json()
	{
		$args = array();
		
		foreach (func_get_args() as $arg)
		{
			if ($arg != '.' && $arg != '..')
				$args[] = urldecode($arg);
		}
		
		$folder_path = PUBLIC_ROOT.DIRECTORY_SEPARATOR. join($args, DIRECTORY_SEPARATOR);
		
		if (PHP_OS == 'WIN' || PHP_OS == 'WINNT')
			$folder_path = iconv('UTF-8', 'CP1251', $folder_path);
		
		if ( ! is_dir($folder_path))
		{
			if (mkdir($folder_path))
			{
				echo json_encode(array('success' => true));
			}
			else
			{
				echo json_encode(array('error' => __('Floder not created!')));
			}
		}
		else
		{
			echo json_encode(array('error' => __('Directory already exists!')));
		}
	}
	
	public function remove()
	{
		$args = array();
		
		foreach (func_get_args() as $arg)
		{
			if ($arg != '.' && $arg != '..')
				$args[] = urldecode($arg);
		}
		
		$path = PUBLIC_ROOT.DIRECTORY_SEPARATOR. join($args, DIRECTORY_SEPARATOR);
		
		if (PHP_OS == 'WIN' || PHP_OS == 'WINNT')
			$path = iconv('UTF-8', 'CP1251', $path);
		
		if (is_dir($path))
		{
			if ($this->_removeRecursive($path))
				Flash::set('success', __('Directory successfully removed!'));
			else
				Flash::set('error', __('Directory not removed!'));
		}
		elseif (is_file($path))
		{
			if (unlink($path))
				Flash::set('success', __('File successfully removed!'));
			else
				Flash::set('error', __('File not removed!'));
		}
		else
		{
			Flash::set('error', __('Path not found!'));
		}
		
		array_pop($args);
		
		redirect(get_url('plugin/file_manager/' .PUBLIC_DIR_NAME.'/'.join($args, '/') ));
	}
	
	private function _removeRecursive( $path )
	{
		if (file_exists($path) && is_dir($path))
		{
			$dirHandle = opendir($path);
			
			while (false !== ($file = readdir($dirHandle))) 
			{
				if ($file != '.' && $file != '..')
				{
					$tmpPath = $path.DIRECTORY_SEPARATOR.$file;
					chmod($tmpPath, 0777);
					
					if (is_dir($tmpPath))
					{
						if ( ! $this->_removeRecursive($tmpPath))
							return false;
					} 
					else 
					{ 
						if (file_exists($tmpPath))
						{
							if ( ! unlink($tmpPath))
								return false;
						}
					}
				}
			}
			
			closedir($dirHandle);
			
			if (file_exists($path))
				rmdir($path);
			else
				return false;
		}
		else
		{
			return false;
		}
		
		return true;
	}
	
	public function rename()
	{
		$args = array();
		
		foreach (func_get_args() as $arg)
		{
			if ($arg != '.' && $arg != '..')
				$args[] = urldecode($arg);
		}
		
		$path = PUBLIC_ROOT.DIRECTORY_SEPARATOR. join($args, DIRECTORY_SEPARATOR);
		
		if (isset($_GET['old_name']) && isset($_GET['new_name']))
		{
			if ($_GET['old_name'] != $_GET['new_name'])
			{
				if (rename($path.DIRECTORY_SEPARATOR.$_GET['old_name'], $path.DIRECTORY_SEPARATOR.$_GET['new_name']))
				{
					Flash::set('success', __('Successfully renamed!'));
				}
			}
			
			if (isset($_GET['new_chmod']) && is_numeric($_GET['new_chmod']))
			{
				chmod($path.DIRECTORY_SEPARATOR.$_GET['new_name'], octdec($_GET['new_chmod']));
			}
		}
		
		redirect(get_url('plugin/file_manager/'.PUBLIC_DIR_NAME.'/'.join($args, '/')));
	}
	
} // end class FilesManagerController