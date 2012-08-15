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
 * @subpackage controllers
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

/**
 * Class LayoutController
 *
 * @package flexo
 * @subpackage controllers
 * 
 * @version 0.1
 * @since 0.1
 */

class LayoutController extends Controller
{
    
    function __construct()
    {
        AuthUser::load();
		
        if ( ! AuthUser::isLoggedIn())
        {
            redirect(get_url('login'));
        }
        else if ( ! AuthUser::hasPermission(array('administrator','developer')))
        {
            Flash::set('error', __('You do not have permission to access the requested page!'));

            if (Setting::get('default_tab') === 'layout')
                redirect(get_url('page'));
            else
                redirect(get_url());
        }
        
        $this->setLayout('backend');
    }
    
    function index()
    {
		#$this->assignToLayout('actions', new View('layout/actions'));
		
        $this->display('layout/index', array(
            'layouts' => Layout::findAll()
        ));
    }
    
    function add()
    {
        // check if trying to save
        if (get_request_method() == 'POST')
            return $this->_add();
        
        // check if user have already enter something
        $layout = Flash::get('post_data');
        
        if (empty($layout))
            $layout = new Layout;
        
        $this->display('layout/edit', array(
            'action'  => 'add',
            'layout' => $layout
        ));
    }
    
    function _add()
    {
        $data = $_POST['layout'];
        Flash::set('post_data', (object) $data);
        
        if (empty($data['name']))
        {
            Flash::set('error', __('You have to specify a name!'));
            redirect(get_url('layout/add/'));
        }
		
        $layout = new Layout($data['name']);
		$layout->content = $data['content'];
        
        if ( ! $layout->save())
        {
            Flash::set('error', __('Layout <b>:name</b> has not been added. Name must be unique!', array(':name' => $layout->name)));
            redirect(get_url('layout/add/'));
        }
        else
        {
            Flash::set('success', __('Layout <b>:name</b> has been added!', array(':name' => $layout->name)));
            Observer::notify('layout_after_add', array($layout));
        }
        
        // save and quit or save and continue editing?
        if (isset($_POST['commit']))
            redirect(get_url('layout'));
        else
            redirect(get_url('layout/edit/'.$layout->name));
    }
    
    function edit($layout_name)
    {
		$layout = new Layout($layout_name);
		
        if ( ! $layout->isExists())
        {
            Flash::set('error', __('Layout <b>:name</b> not found!', array(':name' => $layout->name)));
            redirect(get_url('layout'));
        }
        
        // check if trying to save
        if (get_request_method() == 'POST')
            return $this->_edit($layout);
        
        // display things...
        $this->display('layout/edit', array(
            'action'  => 'edit',
            'layout' => $layout
        ));
    }
    
    function _edit($layout)
    {
		$layout->name = $_POST['layout']['name'];
		$layout->content = $_POST['layout']['content'];
		
        if ( ! $layout->save())
        {
            Flash::set('error', __('Layout <b>:name</b> has not been saved. Name must be unique!', array(':name' => $layout->name)));
        }
        else
        {
            Flash::set('success', __('Layout <b>:name</b> has been saved!', array(':name' => $layout->name)));
            Observer::notify('layout_after_edit', array($layout));
        }
        
        // save and quit or save and continue editing?
        if (isset($_POST['commit']))
            redirect(get_url('layout'));
        else
            redirect(get_url('layout/edit/'.$layout->name));
    }
    
    function delete($layout_name)
    {
		$layout = new Layout($layout_name);
		
        // find the user to delete
        if ( ! $layout->isUsed())
        {
            if ( $layout->delete() )
			{
                Flash::set('success', __('Layout <b>:name</b> has been deleted!', array(':name'=>$layout_name)));
                Observer::notify('layout_after_delete', array($layout_name));
            }
			else
                Flash::set('error', __('Layout <b>:name</b> has not been deleted!', array(':name'=>$layout_name)));
        }
        else
			Flash::set('error', __('Layout <b>:name</b> is used! It <i>can not</i> be deleted!', array(':name'=>$layout_name)));
        
        redirect(get_url('layout'));
    }

} // end LayoutController class