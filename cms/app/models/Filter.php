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
 * @subpackage models
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

/**
 * Class Filter
 *
 * This is a part of the Plugin API of Flexo CMS. It provide a "interface" to
 * add a new filter to the Flexo CMS administration.
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since Flexo version 0.5
 */
class Filter
{
    static $filters = array();
    private static $filters_loaded = array();
    
    /**
     * Add a new filter to Frog CMS
     *
     * @param filter_id string  The Filter plugin folder name
     * @param file      string  The file where the Filter class is
     */
    public static function add($filter_id, $file)
    {
        self::$filters[$filter_id] = $file;
    }
    
    /**
     * Remove a filter to Frog CMS
     *
     * @param filter_id string  The Filter plugin folder name
     */
    public static function remove($filter_id)
    {
        if (isset(self::$filters[$filter_id]))
            unset(self::$filters[$filter_id]);
    }
    
    /**
     * Find all active filters id
     *
     * @return array
     */
    public static function findAll()
    {
        return array_keys(self::$filters);
    }
    
    /**
     * Get a instance of a filter
     *
     * @param filter_id string  The Filter plugin folder name
     *
     * @return mixed   if founded an object, else false
     */
    public static function get($filter_id)
    {
        if ( ! isset(self::$filters_loaded[$filter_id]))
        {
            if (isset(self::$filters[$filter_id]))
            {
                $file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.self::$filters[$filter_id];
                if (file_exists($file))
                {
                    include($file);
                    
                    $filter_class = Inflector::camelize($filter_id);
                    self::$filters_loaded[$filter_id] = new $filter_class();
                    return self::$filters_loaded[$filter_id];
                }
				else
					throw new Exception('Filter file of filter '.$filter_id.' not found!');
            }
            else return false;
        }
        else return self::$filters_loaded[$filter_id];
    }
    
} // end Filter class