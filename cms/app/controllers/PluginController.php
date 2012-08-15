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
 * Class PluginController
 *
 * Plugin controller to dispatch to all plugins controllers.
 *
 * @package flexo
 * @subpackage controllers
 *
 * @since 0.9
 */
class PluginController extends Controller
{
    function __construct()
    {
        AuthUser::load();
		
        if (!AuthUser::isLoggedIn() )
		{
            redirect(get_url('login'));
		}
		else if (!AuthUser::hasPermission(array('administrator','developer','editor')))
		{
			redirect(get_url());
		}
    }
    
    public function render($view, $vars=array())
    {
        if ($this->layout)
        {
            $this->layout_vars['content_for_layout'] = new View('../../'.PLUGINS_DIR_NAME.'/'.$view, $vars);
            return new View('../layouts/'.$this->layout, $this->layout_vars);
        }
        else
			return new View('../../'.PLUGINS_DIR_NAME.'/'.$view, $vars);
    }
    
    public function execute($plugin_controller_name, $params)
    {
        if (isset(Plugin::$controllers[$plugin_controller_name]))
        {
            $plugin = Plugin::$controllers[$plugin_controller_name];
			
			$action = count($params) ? array_shift($params): 'index';
			
            if (file_exists($plugin->file))
            {
                include($plugin->file);
                
                $plugin_controller = new $plugin->class_name;
                
				if (method_exists($plugin_controller, $action))
					call_user_func_array(
						array($plugin_controller, $action),
						$params
					);
				else
					throw new Exception('Action '. $action .' of plugin controller '. $plugin->class_name .' was not found!');
            }
            else
				throw new Exception('Plugin controller file '. $plugin->file .' was not found!');
        }
        else
			throw new Exception('Plugin controller '. $plugin_controller_name .' was not found!');
    }
    
} // end PluginController class