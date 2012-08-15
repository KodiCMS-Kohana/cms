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
 * @subpackage plugins.page_images
 *
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

class PageImagesController extends PluginController
{
	public function upload()
	{
		require(PLUGINS_ROOT.DIRECTORY_SEPARATOR.'file_manager/fileuploader/qqUploader.php');
		
		if (empty($_GET['folder']))
		{
			echo json_encode(array('error' => 'Params folder required!'));
		}
		else
		{
			$folder_name = urldecode(str_replace('..', '', $_GET['folder']));
		
			// Change charset for russian names (only when os windows)
			if (PHP_OS == 'WIN' || PHP_OS == 'WINNT')
				$folder_name = iconv('UTF-8', 'CP1251', $folder_name);
			
			$folder_path = PUBLIC_ROOT.DIRECTORY_SEPARATOR.$folder_name.DIRECTORY_SEPARATOR;
			
			if (is_dir($folder_path) && !empty($folder_name))
			{
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array('png', 'jpg', 'jpeg', 'gif');
				// max file size in bytes
				//$sizeLimit = 10 * 1024 * 1024;
				
				$uploader = new qqFileUploader($allowedExtensions);
				$result = $uploader->handleUpload($folder_path);
				
				$page_id = empty($_GET['page_id']) ? 0: $_GET['page_id'];
				
				if (!empty($result['filename']))
				{
					$image = new PIImage();
					$image->page_id = $page_id;
					$image->file_name = $result['filename'];
				
					if ($image->save())
					{					
						$result['image_id'] = $image->id;
						
						// to pass data through iframe you will need to encode all html tags
						echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
					}
					else
					{
						echo json_encode(array('error' => 'Image not saved!'));
					}
				}
				else
				{
					echo json_encode(array('error' => 'Image not uploaded!'));
				}
			}
			else
			{
				echo json_encode(array('error' => 'Folder \''.$folder_name.'\' not valid!'));
			}
		}
	}
	
	public function delete( $image_id )
	{
		$image = Record::findOneFrom('PIImage', 'id="'. (int)$image_id .'"');
		
		if( $image->delete() )
		{
			echo $this->renderJSON( array('success' => $image_id) );
		}
		else
		{
			echo $this->renderJSON( array('error' => 'Image not deleted!') );
		}
	}
    
    public function getImagesItems($page_id)
    {
        $images = Record::findAllFrom('PIImage', 'page_id="'. (int)$page_id .'" ORDER BY position ASC');
        foreach ($images as $item) {
            echo new View('../../'.PLUGINS_DIR_NAME.'/page_images/views/_image_item', array('item'=>$item));
        }
    }
    
    public function changePosition($page_id)
    {
        if (!isset($_POST['position'])) {
            echo $this->renderJSON( array('error' => 'Position empty!') );
        }
        foreach ($_POST['position'] as $i=>$id) {
            $image = Record::findByIdFrom('PIImage', $id);
            if ($image) {
                $image->position = $i;
                $image->save();
            }
        }
        echo $this->renderJSON( array('success' => true) );
    }
} // end class PageImagesController