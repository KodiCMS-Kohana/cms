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
 * class Setting 
 *
 * Provide a administration interface of some configuration
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since Flexo version 0.8.7
 */
class Setting extends Record
{
    const TABLE_NAME = 'setting';
    
    public $name;
    public $value;
    
    public static $settings = array();
    public static $is_loaded = false;
    
    public static function init()
    {
        if (! self::$is_loaded)
        {
            $settings = Record::findAllFrom('Setting');
            foreach($settings as $setting)
                self::$settings[$setting->name] = $setting->value;
            
            self::$is_loaded = true;
        }
    }
    
    /**
     * Get the value of a setting
     *
     * @param name  string  The setting name
     * @return string the value of the setting name
     */
    public static function get($name)
    {
        return isset(self::$settings[$name]) ? self::$settings[$name]: false;
    }
    
    public static function saveFromData($data)
    {
        $tablename = self::tableNameFromClassName('Setting');
        
        foreach( $data as $name => $value )
        {
            $sql = 'UPDATE '. $tablename .' SET value='. self::$__CONN__->quote($value)
                 . ' WHERE name='.self::$__CONN__->quote($name);
            self::$__CONN__->exec($sql);
        }
    }
    
    public static function getThemes()
    {
        $themes = array();
        $dir = CMS_ROOT.DIRECTORY_SEPARATOR.ADMIN_DIR_NAME.DIRECTORY_SEPARATOR.'themes';
		
        if (is_dir($dir) && $handle = opendir($dir))
        {
            while ($file = readdir($handle))
            {
                if (is_dir($dir.DIRECTORY_SEPARATOR.$file) && $file != '.' && $file != '..')
                {
                    $themes[$file] = Inflector::humanize($file);
                }
            }
            closedir($handle);
        }
        asort($themes);
        
        return $themes;
    }

} // end Setting class