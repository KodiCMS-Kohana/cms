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
 * Class SnippetController
 *
 * @package flexo
 * @subpackage controllers
 *
 * @since 0.1
 */
class SnippetController extends Controller
{

    public function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
        {
            redirect(get_url('login'));
        }
        else if ( ! AuthUser::hasPermission(array('administrator','developer')))
        {
            Flash::set('error', __('You do not have permission to access the requested page!'));

            if (Setting::get('default_tab') === 'snippet')
                redirect(get_url('page'));
            else
                redirect(get_url());
        }
		
        $this->setLayout('backend');
    }
    
    public function index()
    {		
		#$this->assignToLayout('actions', new View('snippet/actions'));
		
        $this->display('snippet/index', array(
            'snippets' => Snippet::findAll()
        ));
    }
    
    public function add()
    {
        // check if trying to save
        if (get_request_method() == 'POST')
            return $this->_add();
        
        // check if user have already enter something
        $snippet = Flash::get('post_data');
        
        if (empty($snippet))
            $snippet = new Snippet;
        
        $this->display('snippet/edit', array(
            'action'  => 'add',
            'filters' => Filter::findAll(),
            'snippet' => $snippet
        ));
    }
    
    private function _add()
    {
        $data = $_POST['snippet'];
        Flash::set('post_data', (object) $data);
        
        $snippet = new Snippet($data['name']);
        $snippet->content = $data['content'];
		
        if ( ! $snippet->save())
        {
            Flash::set('error', __('Snippet <b>:name</b> has not been added. Name must be unique!', array(':name' => $snippet->name)));
            redirect(get_url('snippet/add'));
        }
        else
        {
            Flash::set('success', __('Snippet <b>:name</b> has been added!', array(':name' => $snippet->name)));
            Observer::notify('snippet_after_add', array($snippet));
        }
        
        // save and quit or save and continue editing?
        if (isset($_POST['commit']))
            redirect(get_url('snippet'));
        else
            redirect(get_url('snippet/edit/'.$snippet->name));
    }
    
    public function edit($snippet_name)
    {
		$snippet = new Snippet($snippet_name);
		
        if ( ! $snippet->isExists() )
        {
            Flash::set('error', __('Snippet <b>:name</b> not found!', array(':name' => $snippet->name)));
            redirect(get_url('snippet'));
        }
        
        // check if trying to save
        if (get_request_method() == 'POST')
            return $this->_edit($snippet_name);
        
        $this->display('snippet/edit', array(
            'action'  => 'edit',
            'filters' => Filter::findAll(),
            'snippet' => $snippet
        ));
    }
    
    private function _edit($snippet_name)
    {
        $data = $_POST['snippet'];
        
        $snippet = new Snippet($snippet_name);
		$snippet->name = $data['name'];
        $snippet->content = $data['content'];
		
        if ( ! $snippet->save())
        {
            Flash::set('error', __('Snippet <b>:name</b> has not been saved. Name must be unique!', array(':name'=>$snippet->name)));
            redirect(get_url('snippet/edit/'.$snippet->name));
        }
        else
        {
            Flash::set('success', __('Snippet <b>:name</b> has been saved!', array(':name'=>$snippet->name)));
            Observer::notify('snippet_after_edit', array($snippet));
        }
        
        // save and quit or save and continue editing?
        if (isset($_POST['commit']))
            redirect(get_url('snippet'));
        else
            redirect(get_url('snippet/edit/'.$snippet->name));
    }
    
    public function delete($snippet_name)
    {
		$snippet = new Snippet($snippet_name);
		
        // find the user to delete
        if ($snippet->isExists())
        {
            if ($snippet->delete())
            {
                Flash::set('success', __('Snippet <b>:name</b> has been deleted!', array(':name'=>$snippet->name)));
                Observer::notify('snippet_after_delete', array($snippet));
            }
            else
                Flash::set('error', __('Snippet <b>:name</b> has not been deleted!', array(':name'=>$snippet->name)));
        }
        else Flash::set('error', __('Snippet not found!'));
        
        redirect(get_url('snippet'));
    }

} // end SnippetController class