<?php if(!defined('CMS_ROOT')) die;

/**
 * Flexo CMS - Content Management System. <http://flexo.up.dn.ua>
 * Copyright (C) 2008 Maslakov Alexander <jmas.ukraine@gmail.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Frog CMS.
 *
 * Frog CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Frog CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Frog CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Frog CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * The Framework file is a modified version of the so-called Green Framework.
 * 
 * @package framework
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @version 1.6
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Philippe Archambault, 2008
 *
 * @todo Replace the customized Framework with the latest uncustomized Green Framework?
 */

/**
 * 
 */
define('FRAMEWORK_STARTING_MICROTIME', get_microtime());

// All constants that can be defined before customizing your framework
if (!defined('DEBUG'))              define('DEBUG', false);

if (!defined('CORE_ROOT'))          define('CORE_ROOT',   dirname(__FILE__));

if (!defined('APP_PATH'))           define('APP_PATH',    CORE_ROOT.DIRECTORY_SEPARATOR.'app');
if (!defined('HELPER_PATH'))        define('HELPER_PATH', CORE_ROOT.DIRECTORY_SEPARATOR.'helpers');

if (!defined('BASE_URL'))           define('BASE_URL', 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']) .'/');

if (!defined('DEFAULT_CONTROLLER')) define('DEFAULT_CONTROLLER', 'index');
if (!defined('DEFAULT_ACTION'))     define('DEFAULT_ACTION', 'index');

if (!defined('DEFAULT_TIMEZONE'))   define('DEFAULT_TIMEZONE', 'Europe/Helsinki');

// Setting error display depending on debug mode or not
error_reporting((DEBUG ? E_ALL : 0));

// No more quotes escaped with a backslash
if( PHP_VERSION < 5.3 )
    @set_magic_quotes_runtime(0);

if( !isset($_SESSION) )
{	
	if( isset($_POST['PHPSESSID']) )
		session_id($_POST['PHPSESSID']);
	
    session_start();
}

ini_set('date.timezone', DEFAULT_TIMEZONE);
if(function_exists('date_default_timezone_set'))
    date_default_timezone_set(DEFAULT_TIMEZONE);
else
    putenv('TZ='.DEFAULT_TIMEZONE);

	
	
/**
 * The Dispatcher main Core class is responsible for mapping urls/routes to Controller methods.
 * 
 * Each route that has the same number of directory components as the current
 * requested url is tried, and the first method that returns a response with a
 * non false/non null value will be returned via the Dispatcher::dispatch() method.
 *
 * For example:
 *
 * A route string can be a literal url such as '/pages/about' or can contain
 * wildcards (:any or :num) and/or regex like '/blog/:num' or '/page/:any'.
 *
 * <code>Dispatcher::addRoute(array(
 *  '/' => 'page/index',
 *  '/about' => 'page/about,
 *  '/blog/:num' => 'blog/post/$1',
 *  '/blog/:num/comment/:num/delete' => 'blog/deleteComment/$1/$2'
 * ));</code>
 *
 * Visiting /about/ would call PageController::about(),
 * visiting /blog/5 would call BlogController::post(5)
 * visiting /blog/5/comment/42/delete would call BlogController::deleteComment(5,42)
 *
 * The dispatcher is used by calling Dispatcher::addRoute() to setup the route(s),
 * and Dispatcher::dispatch() to handle the current request and get a response.
 */
final class Dispatcher
{
    private static $routes = array();
    private static $params = array();
    private static $status = array();
    private static $requested_url = '';
    
    public static function addRoute($route, $destination=null)
    {
        if ($destination != null && !is_array($route)) {
            $route = array($route => $destination);
        }
        self::$routes = array_merge(self::$routes, $route);
    }
    
    public static function splitUrl($url)
    {
        return preg_split('/\//', $url, -1, PREG_SPLIT_NO_EMPTY);
    }
    
    public static function dispatch($requested_url = null, $default = null)
    {
        // If no url passed, we will get the first key from the _GET array
        // that way, index.php?/controller/action/var1&email=example@example.com
        // requested_url will be equal to: /controller/action/var1
        if ($requested_url === null)
		{
            $pos = strpos($_SERVER['QUERY_STRING'], '&');
            if ($pos !== false)
			{
                $requested_url = substr($_SERVER['QUERY_STRING'], 0, $pos);
            }
			else
			{
                $requested_url = $_SERVER['QUERY_STRING'];
            }
        }

        // If no URL is requested (due to someone accessing admin section for the first time)
        // AND $default is setAllow for a default tab
        if ($requested_url == null && $default != null)
		{
            $requested_url = $default;
        }
        
        // Requested url MUST start with a slash (for route convention)
        if (strpos($requested_url, '/') !== 0)
		{
            $requested_url = '/' . $requested_url;
        }
        
        self::$requested_url = $requested_url;
        
        // This is only trace for debugging
        self::$status['requested_url'] = $requested_url;
        
        // Make the first split of the current requested_url
        self::$params = self::splitUrl($requested_url);
        
        // Do we even have any custom routing to deal with?
        if (count(self::$routes) === 0)
		{
            return self::executeAction(self::getController(), self::getAction(), self::getParams());
        }
        
        // Is there a literal match? If so we're done
        if (isset(self::$routes[$requested_url]))
		{
            self::$params = self::splitUrl(self::$routes[$requested_url]);
            return self::executeAction(self::getController(), self::getAction(), self::getParams());
        }
        
        // Loop through the route array looking for wildcards
        foreach (self::$routes as $route => $uri)
		{
            // Convert wildcards to regex
            if (strpos($route, ':') !== false)
			{
                $route = str_replace(':any', '(.+)', str_replace(':num', '([0-9]+)', $route));
            }
            
			// Does the regex match?
            if (preg_match('#^'.$route.'$#', $requested_url))
			{
                // Do we have a back-reference?
                if (strpos($uri, '$') !== false && strpos($route, '(') !== false)
				{
                    $uri = preg_replace('#^'.$route.'$#', $uri, $requested_url);
                }
                self::$params = self::splitUrl($uri);
                // We found it, so we can break the loop now!
                break;
            }
        }
        
        return self::executeAction(self::getController(), self::getAction(), self::getParams());
    } // Dispatch
    
    public static function getCurrentUrl()
    {
        return self::$requested_url;
    }
    
    public static function getController()
    {
        // Check for settable default controller
        // if it's a plugin and not activated, revert to Frog hardcoded default
        if (isset(self::$params[0]) && self::$params[0] == 'plugin' )
        {
            $loaded_plugins = Plugin::$plugins;
            if (isset(self::$params[1]) && !isset($loaded_plugins[self::$params[1]]))
			{
                unset(self::$params[0]);
                unset(self::$params[1]);
            }
        }        

        return isset(self::$params[0]) ? self::$params[0]: DEFAULT_CONTROLLER;
    }
        
    public static function getAction()
    {
        return isset(self::$params[1]) ? self::$params[1]: DEFAULT_ACTION;
    }
    
    public static function getParams()
    {
        return array_slice(self::$params, 2);
    }
    
    public static function getStatus($key=null)
    {
        return ($key === null) ? self::$status: (isset(self::$status[$key]) ? self::$status[$key]: null);
    }
    
    public static function executeAction($controller, $action, $params)
    {
        self::$status['controller'] = $controller;
        self::$status['action'] = $action;
        self::$status['params'] = implode(', ', $params);
        
        $controller_class = Inflector::camelize($controller);
        $controller_class_name = $controller_class . 'Controller';
        
        // Get an instance of that controller
        if (class_exists($controller_class_name))
		{
            $controller_object = new $controller_class_name();
			
			if (!$controller_object instanceof Controller)
			{
				throw new Exception('Class '.$controller_class_name.' does not extends Controller class!');
			}

			// Execute the action
			$controller_object->execute($action, $params);
        }
		else
		{
			throw new Exception('Class '.$controller_class_name.' not found!');
		}
    }

} // end Dispatcher class


/**
 * The Record class represents a database record.
 * 
 * It is used as an abstraction layer so classes don't need to implement their own
 * database functionality.
 */
class Record
{
    const PARAM_BOOL = 5;
    const PARAM_NULL = 0;
    const PARAM_INT = 1;
    const PARAM_STR = 2;
    const PARAM_LOB = 3;
    const PARAM_STMT = 4;
    const PARAM_INPUT_OUTPUT = -2147483648;
    const PARAM_EVT_ALLOC = 0;
    const PARAM_EVT_FREE = 1;
    const PARAM_EVT_EXEC_PRE = 2;
    const PARAM_EVT_EXEC_POST = 3;
    const PARAM_EVT_FETCH_PRE = 4;
    const PARAM_EVT_FETCH_POST = 5;
    const PARAM_EVT_NORMALIZE = 6;
    
    const FETCH_LAZY = 1;
    const FETCH_ASSOC = 2;
    const FETCH_NUM = 3;
    const FETCH_BOTH = 4;
    const FETCH_OBJ = 5;
    const FETCH_BOUND = 6;
    const FETCH_COLUMN = 7;
    const FETCH_CLASS = 8;
    const FETCH_INTO = 9;
    const FETCH_FUNC = 10;
    const FETCH_GROUP = 65536;
    const FETCH_UNIQUE = 196608;
    const FETCH_CLASSTYPE = 262144;
    const FETCH_SERIALIZE = 524288;
    const FETCH_PROPS_LATE = 1048576;
    const FETCH_NAMED = 11;
    
    const ATTR_AUTOCOMMIT = 0;
    const ATTR_PREFETCH = 1;
    const ATTR_TIMEOUT = 2;
    const ATTR_ERRMODE = 3;
    const ATTR_SERVER_VERSION = 4;
    const ATTR_CLIENT_VERSION = 5;
    const ATTR_SERVER_INFO = 6;
    const ATTR_CONNECTION_STATUS = 7;
    const ATTR_CASE = 8;
    const ATTR_CURSOR_NAME = 9;
    const ATTR_CURSOR = 10;
    const ATTR_ORACLE_NULLS = 11;
    const ATTR_PERSISTENT = 12;
    const ATTR_STATEMENT_CLASS = 13;
    const ATTR_FETCH_TABLE_NAMES = 14;
    const ATTR_FETCH_CATALOG_NAMES = 15;
    const ATTR_DRIVER_NAME = 16;
    const ATTR_STRINGIFY_FETCHES = 17;
    const ATTR_MAX_COLUMN_LEN = 18;
    const ATTR_EMULATE_PREPARES = 20;
    const ATTR_DEFAULT_FETCH_MODE = 19;
    
    const ERRMODE_SILENT = 0;
    const ERRMODE_WARNING = 1;
    const ERRMODE_EXCEPTION = 2;
    const CASE_NATURAL = 0;
    const CASE_LOWER = 2;
    const CASE_UPPER = 1;
    const NULL_NATURAL = 0;
    const NULL_EMPTY_STRING = 1;
    const NULL_TO_STRING = 2;
    const ERR_NONE = '00000';
    const FETCH_ORI_NEXT = 0;
    const FETCH_ORI_PRIOR = 1;
    const FETCH_ORI_FIRST = 2;
    const FETCH_ORI_LAST = 3;
    const FETCH_ORI_ABS = 4;
    const FETCH_ORI_REL = 5;
    const CURSOR_FWDONLY = 0;
    const CURSOR_SCROLL = 1;
    const MYSQL_ATTR_USE_BUFFERED_QUERY = 1000;
    const MYSQL_ATTR_LOCAL_INFILE = 1001;
    const MYSQL_ATTR_INIT_COMMAND = 1002;
    const MYSQL_ATTR_READ_DEFAULT_FILE = 1003;
    const MYSQL_ATTR_READ_DEFAULT_GROUP = 1004;
    const MYSQL_ATTR_MAX_BUFFER_SIZE = 1005;
    const MYSQL_ATTR_DIRECT_QUERY = 1006;
    
    public static $__CONN__ = false;
    public static $__QUERIES__ = array();
    
    final public static function connection($connection)
    {
        self::$__CONN__ = $connection;
    }
    
    final public static function getConnection()
    {
        return self::$__CONN__;
    }
    
    final public static function logQuery($sql)
    {
        self::$__QUERIES__[] = $sql;
    }
    
    final public static function getQueryLog()
    {
        return self::$__QUERIES__;
    }
    
    final public static function getQueryCount()
    {
        return count(self::$__QUERIES__);
    }
    
    final public static function query($sql, $values=false)
    {
        self::logQuery($sql);
        
        if (is_array($values))
		{
            $stmt = self::$__CONN__->prepare($sql);
            $stmt->execute($values);
            return $stmt->fetchAll(self::FETCH_OBJ);
        }
		else
		{
            return self::$__CONN__->query($sql);
        }
    }
    
    final public static function tableNameFromClassName($class_name)
    {
        try
        {
            if (class_exists($class_name) && defined($class_name.'::TABLE_NAME'))
                return TABLE_PREFIX.constant($class_name.'::TABLE_NAME');
        }
        catch (Exception $e)
        {
            return TABLE_PREFIX.Inflector::underscore($class_name);
        }
    }
    
    final public static function escape($value)
    {
        return self::$__CONN__->quote($value);
    }
    
    final public static function lastInsertId()
    {
        return self::$__CONN__->lastInsertId();
    }
    
    public function __construct($data=false)
    {
        if (is_array($data))
            $this->setFromData($data);
    }
    
    public function setFromData($data)
    {
        foreach($data as $key => $value)
		{
            $this->$key = $value;
        }
    }
    
    /**
     * Generates an insert or update string from the supplied data and executes it
     *
     * @return boolean
     */
    public function save()
    {
        if ( ! $this->beforeSave()) return false;
        
        $value_of = array();

        if( empty($this->id) )
		{            
            if ( ! $this->beforeInsert()) return false;
            
            $columns = $this->getColumns();
            
            // Escape and format for SQL insert query
            foreach ($columns as $column)
			{
                if (isset($this->$column))
				{
                    $value_of[$column] = self::$__CONN__->quote($this->$column);
                }
            }
            
            $sql = 'INSERT INTO '.self::tableNameFromClassName(get_class($this)).' ('
                 . implode(', ', array_keys($value_of)).') VALUES ('.implode(', ', array_values($value_of)).')';
			
            $return = self::$__CONN__->exec($sql) !== false;
            $this->id = self::lastInsertId(); 
             
            if ( ! $this->afterInsert()) return false;
        
        }
		else
		{
            if ( ! $this->beforeUpdate()) return false;
            
            $columns = $this->getColumns();
            
            // Escape and format for SQL update query
            foreach( $columns as $column )
			{
                if( isset($this->$column) )
				{
                    $value_of[$column] = $column.'='.self::$__CONN__->quote($this->$column);
                }
            }
            
            unset($value_of['id']);
            
            $sql = 'UPDATE '.self::tableNameFromClassName(get_class($this)).' SET '
                 . implode(', ', $value_of).' WHERE id = '.$this->id;
			
            $return = self::$__CONN__->exec($sql) !== false;
            
            if( ! $this->afterUpdate() ) return false;
        }
        
        self::logQuery($sql);
        
        // Run it !!...
        return $return;
    }

    /**
     * Generates a delete string and executes it
     *
     * @param string $table the table name
     * @param string $where the query condition
     * @return boolean
     */
    public function delete()
    {
        if ( ! $this->beforeDelete()) return false;
        $sql = 'DELETE FROM '.self::tableNameFromClassName(get_class($this))
             . ' WHERE id='.self::$__CONN__->quote($this->id);

        // Run it !!...
        $return = self::$__CONN__->exec($sql) !== false;
        if ( ! $this->afterDelete()) {
            $this->save();
            return false;
        }
        
        self::logQuery($sql);
        
        return $return;
    }
    
    public function beforeSave() { return true; }
    public function beforeInsert() { return true; }
    public function beforeUpdate() { return true; }
    public function beforeDelete() { return true; }
    public function afterSave() { return true; }
    public function afterInsert() { return true; }
    public function afterUpdate() { return true; }
    public function afterDelete() { return true; }
    
    /**
     * Return an array of all columns in the table
     * It is a good idea to rewrite this method in all your model classes;
     * used in save() for creating the insert and/or update sql query
     */
    public function getColumns()
    {
        return array_keys(get_object_vars($this));
    }
    
    public static function insert($class_name, $data)
    {
        $keys = array();
        $values = array();
        
        foreach ($data as $key => $value) {
            $keys[] = $key;
            $values[] = self::$__CONN__->quote($value);
        }
        
        $sql = 'INSERT INTO '.self::tableNameFromClassName($class_name).' ('.join(', ', $keys).') VALUES ('.join(', ', $values).')';
        
        self::logQuery($sql);
        
        // Run it !!...
        return self::$__CONN__->exec($sql) !== false;
    }
    
    public static function update($class_name, $data, $where, $values=array())
    {
        $setters = array();
        
        // Prepare request by binding keys
        foreach ($data as $key => $value) {
            $setters[] = $key.'='.self::$__CONN__->quote($value);
        }
        
        $sql = 'UPDATE '.self::tableNameFromClassName($class_name).' SET '.join(', ', $setters).' WHERE '.$where;
        
        self::logQuery($sql);
        
        $stmt = self::$__CONN__->prepare($sql);
        return $stmt->execute($values);
    }
    
    public static function deleteWhere($class_name, $where, $values=array())
    {
        $sql = 'DELETE FROM '.self::tableNameFromClassName($class_name).' WHERE '.$where;
        
        self::logQuery($sql);
        
        $stmt = self::$__CONN__->prepare($sql);
        return $stmt->execute($values);
    }
    
    //
    // Note: lazy finder or getter method. Pratical when you need something really 
    //       simple no join or anything will only generate simple select * from table ...
    //
    
    public static function findByIdFrom($class_name, $id)
    {
        return self::findOneFrom($class_name, 'id=?', array($id));
    }
    
    public static function findOneFrom($class_name, $where, $values=array())
    {
        $sql = 'SELECT * FROM '.self::tableNameFromClassName($class_name).' WHERE '.$where;
        
        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute($values);
        
        self::logQuery($sql);
        
        return $stmt->fetchObject($class_name);
    }
    
    public static function findAllFrom($class_name, $where=false, $values=array())
    {
        $sql = 'SELECT * FROM '.self::tableNameFromClassName($class_name).($where ? ' WHERE '.$where:'');
        
        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute($values);
        
        self::logQuery($sql);
        
        $objects = array();
        while ($object = $stmt->fetchObject($class_name))
            $objects[] = $object;
        
        return $objects;
    }
    
    public static function countFrom($class_name, $where=false, $values=array())
    {
        $sql = 'SELECT COUNT(*) AS nb_rows FROM '.self::tableNameFromClassName($class_name).($where ? ' WHERE '.$where:'');
        
        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute($values);
        
        self::logQuery($sql);
        
        return (int) $stmt->fetchColumn();
    }

}

/**
 * The template object takes a valid path to a template file as the only argument
 * in the constructor. You can then assign properties to the template, which
 * become available as local variables in the template file. You can then call
 * display() to get the output of the template, or just call print on the template
 * directly thanks to PHP 5's __toString magic method.
 * 
 * echo new View('my_template',array(
 *  'title' => 'My Title',
 *  'body' => 'My body content'
 * ));
 * 
 * my_template.php might look like this: 
 * 
 * <html>
 * <head>
 *  <title><?php echo $title;?></title>
 * </head>
 * <body>
 *  <h1><?php echo $title;?></h1>
 *  <p><?php echo $body;?></p>
 * </body>
 * </html>
 * 
 * Using view helpers:
 * 
 * use_helper('HelperName', 'OtherHelperName');
 */
class View
{
    private $file;           // String of template file
    private $vars = array(); // Array of template variables

    /**
     * Assign the template path
     *
     * @param string $file Template path (absolute path or path relative to the templates dir)
     * @return void
     */
    public function __construct($file, $vars=false)
    {
        $this->file = APP_PATH.'/views/'.ltrim($file, '/').'.php';
        
        if ( ! file_exists($this->file)) {
            throw new Exception("View '{$this->file}' not found!");
        }
        
        if ($vars !== false) {
            $this->vars = $vars;
        }
    }

    /**
     * Assign specific variable to the template
     *
     * @param mixed $name Variable name
     * @param mixed $value Variable value
     * @return void
     */
    public function assign($name, $value=null)
    {
        if (is_array($name)) {
            array_merge($this->vars, $name);
        } else {
            $this->vars[$name] = $value;
        }
    } // assign

    /**
     * Display template and return output as string
     *
     * @return string content of compiled view template
     */
    public function render()
    {
        ob_start();
        
        extract($this->vars, EXTR_SKIP);
        include $this->file;
        
        $content = ob_get_clean();
        return $content;
    }

    /**
     * Display the rendered template
     */
    public function display() { echo $this->render(); }

    /**
     * Render the content and return it
     * ex: echo new View('blog', array('title' => 'My title'));
     *
     * @return string content of the view
     */
    public function __toString() { return $this->render(); }

} // end View class


/**
 * The Controller class should be the parent class of all of your Controller sub classes
 * that contain the business logic of your application (render a blog post, log a user in,
 * delete something and redirect, etc).
 *
 * In the Frog class you can define what urls / routes map to what Controllers and
 * methods. Each method can either:
 *
 * - return a string response
 * - redirect to another method
 */
class Controller
{
    protected $layout = false;
    protected $layout_vars = array();
    
    public function execute($action, $params)
    {
        // it's a private method of the class or action is not a method of the class
        if (substr($action, 0, 1) == '_' || ! method_exists($this, $action)) {
            throw new Exception("Action '{$action}' is not valid!");
        }
        call_user_func_array(array($this, $action), $params);
    }
    
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
    
    public function assignToLayout($var, $value)
    {
        if (is_array($var)) {
            array_merge($this->layout_vars, $var);
        } else {
            $this->layout_vars[$var] = $value;
        }
    }
    
    public function render($view, $vars=array())
    {
        if ($this->layout) {
            $this->layout_vars['content_for_layout'] = new View($view, $vars);
            return new View('../layouts/'.$this->layout, $this->layout_vars);
        } else {
            return new View($view, $vars);
        }
    }
    
    public function display($view, $vars=array(), $exit=true)
    {
        echo $this->render($view, $vars);
        
        if ($exit) exit;
    }

    public function renderJSON($data_to_encode)
    {
		if( function_exists('json_encode') )
            return json_encode($data_to_encode);
		elseif( class_exists('JSON') )
            return JSON::encode($data_to_encode);
		else
            throw new Exception('No function or class found to render JSON.');
    }
    
} // end Controller class

final class Observer
{
    static protected $events = array();
    
    public static function observe($event_name, $callback)
    {
        if ( ! isset(self::$events[$event_name]))
            self::$events[$event_name] = array();
        
        self::$events[$event_name][$callback] = $callback;
    }
    
    public static function stopObserving($event_name, $callback)
    {  
        if (isset(self::$events[$event_name][$callback]))
            unset(self::$events[$event_name][$callback]);
    }
    
    public static function clearObservers($event_name)
    {
        self::$events[$event_name] = array();
    }
    
    public static function getObserverList($event_name)
    {
        return (isset(self::$events[$event_name])) ? self::$events[$event_name] : array();
    }
    
    /**
     * If your event does not need to process the return values from any observers use this instead of getObserverList()
     */
    public static function notify($event_name, $args = array())
    {
        //$args = array_slice(func_get_args(), 1); // removing event name from the arguments
        
        foreach (self::getObserverList($event_name) as $callback)
            call_user_func_array($callback, $args);
    }
}

/**
 * The AutoLoader class is an object oriented hook into PHP's __autoload functionality. You can add
 * 
 * - Single Files AutoLoader::addFile('Blog','/path/to/Blog.php');
 * - Multiple Files AutoLoader::addFile(array('Blog'=>'/path/to/Blog.php','Post'=>'/path/to/Post.php'));
 * - Whole Folders AutoLoader::addFolder('path');
 *
 * When adding a whole folder each file should contain one class named the same as the file without ".php" (Blog => Blog.php)
 */
class AutoLoader
{
    protected static $files = array();
    protected static $folders = array();
    
	/**
	* AutoLoader::addFile('Blog','/path/to/Blog.php');
	* AutoLoader::addFile(array('Blog'=>'/path/to/Blog.php','Post'=>'/path/to/Post.php'));
	* @param mixed $class_name string class name, or array of class name => file path pairs.
	* @param mixed $file Full path to the file that contains $class_name.
	*/
    public static function addFile($class_name, $file=null)
    {
        if ($file == null && is_array($class_name))
            array_merge(self::$files, $class_name);
        else
            self::$files[$class_name] = $file;
    }
    
	/**
	* AutoLoader::addFolder('/path/to/my_classes/');
	* AutoLoader::addFolder(array('/path/to/my_classes/','/more_classes/over/here/'));
	* @param mixed $folder string, full path to a folder containing class files, or array of paths.
	*/
    public static function addFolder($folder)
    {
        if ( ! is_array($folder))
            $folder = array($folder);
		
        self::$folders = array_merge(self::$folders, $folder);
    }
    
    public static function load($class_name)
    {
        if (isset(self::$files[$class_name]))
		{
            if (file_exists(self::$files[$class_name]))
			{
                require self::$files[$class_name];
                return;
            }
        }
		else
		{
            foreach (self::$folders as $folder)
			{
                $folder = rtrim($folder, DIRECTORY_SEPARATOR);
                $file = $folder.DIRECTORY_SEPARATOR.$class_name.'.php';
				
                if (file_exists($file))
				{
                    require($file);
                    return;
                }
            }
        }
		
		/*if( DEBUG )
			throw new Exception("AutoLoader did not find file for '{$class_name}'!");
		else*/
			return false;
    }
    
} // end AutoLoader class

AutoLoader::addFolder(array(APP_PATH.DIRECTORY_SEPARATOR.'models',
							APP_PATH.DIRECTORY_SEPARATOR.'controllers'));

if (function_exists('spl_autoload_register'))
{
	spl_autoload_register('AutoLoader::load', true);
}
elseif ( !function_exists('__autoload') )
{
    function __autoload($class_name)
    {
		AutoLoader::load($class_name);
    }
}

/**
 * Flash service
 *
 * Purpose of this service is to make some data available across pages. Flash
 * data is available on the next page but deleted when execution reach its end.
 *
 * Usual use of Flash is to make it possible for the current page to pass some data
 * to the next one (for instance success or error message before HTTP redirect).
 *
 * Flash::set('errors', 'Blog not found!');
 * Flass::set('success', 'Blog has been saved with success!');
 * Flash::get('success');
 *
 * Flash service as a concept is taken from Rails. This thing is really useful!
 */
final class Flash
{
    const SESSION_KEY = 'framework_flash';
    
    private static $_previous = array(); // Data that prevous page left in the Flash

    /**
     * Return specific variable from the flash. If value is not found NULL is
     * returned
     *
     * @param string $var Variable name
     * @return mixed
     */
    public static function get($var)
    {
        return isset(self::$_previous[$var]) ? self::$_previous[$var] : null;
    }

    /**
     * Add specific variable to the flash. This variable will be available on the
     * next page unless removed with the removeVariable() or clear() method
     *
     * @param string $var Variable name
     * @param mixed $value Variable value
     * @return void
     */
    public static function set($var, $value)
    {
        $_SESSION[self::SESSION_KEY][$var] = $value;
    } // set

    /**
     * Call this function to clear flash. Note that data that previous page
     * stored will not be deleted - just the data that this page saved for
     * the next page
     *
     * @param none
     * @return void
     */
    public static function clear()
    {
        $_SESSION[self::SESSION_KEY] = array();
    } // clear

    /**
     * This function will read flash data from the $_SESSION variable
     * and load it into $this->previous array
     *
     * @param none
     * @return void
     */
    public static function init()
    {
        // Get flash data...
        if( !empty($_SESSION[self::SESSION_KEY]) && is_array($_SESSION[self::SESSION_KEY]) )
            self::$_previous = $_SESSION[self::SESSION_KEY];

        $_SESSION[self::SESSION_KEY] = array();
    }

} // end Flash class


final class Inflector 
{
    /**
     *  Return an CamelizeSyntaxed (LikeThisDearReader) from something like_this_dear_reader.
     *
     * @param string $string Word to camelize
     * @return string Camelized word. LikeThis.
     */
    public static function camelize($string)
    {
        return str_replace(' ','',ucwords(str_replace('_',' ', $string)));
    }

    /**
     * Return an underscore_syntaxed (like_this_dear_reader) from something LikeThisDearReader.
     *
     * @param  string $string CamelCased word to be "underscorized"
     * @return string Underscored version of the $string
     */
    public static function underscore($string)
    {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $string));
    }
    
    /**
     * Return a Humanized syntaxed (Like this dear reader) from something like_this_dear_reader.
     *
     * @param  string $string CamelCased word to be "underscorized"
     * @return string Underscored version of the $string
     */
    public static function humanize($string)
    {
        return ucfirst(str_replace('_', ' ', $string));
    }
}

class URL {
	
	public static function title($title, $separator = '_')
	{
		$title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', strtolower($title));

		// Replace all separator characters and whitespace by a single separator
		$title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

		// Trim separators from the beginning and end
		return trim($title, $separator);
	}
	
	public static function query(array $params = NULL, $use_get = TRUE)
	{
		if ($use_get)
		{
			if ($params === NULL)
			{
				// Use only the current parameters
				$params = $_GET;
			}
			else
			{
				// Merge the current and new parameters
				$params = array_merge($_GET, $params);
			}
		}

		if (empty($params))
		{
			// No query parameters
			return '';
		}

		// Note: http_build_query returns an empty string for a params array with only NULL values
		$query = http_build_query($params, '', '&');

		// Don't prepend '?' to an empty string
		return ($query === '') ? '' : ('?'.$query);
	}
}

class Arr {
	
	/**
	 * @var  string  default delimiter for path()
	 */
	public static $delimiter = '.';

	/**
	 * Tests if an array is associative or not.
	 *
	 *     // Returns TRUE
	 *     Arr::is_assoc(array('username' => 'john.doe'));
	 *
	 *     // Returns FALSE
	 *     Arr::is_assoc('foo', 'bar');
	 *
	 * @param   array   array to check
	 * @return  boolean
	 */
	public static function is_assoc(array $array)
	{
		// Keys of the array
		$keys = array_keys($array);

		// If the array keys of the keys match the keys, then the array must
		// not be associative (e.g. the keys array looked like {0:0, 1:1...}).
		return array_keys($keys) !== $keys;
	}

	/**
	 * Test if a value is an array with an additional check for array-like objects.
	 *
	 *     // Returns TRUE
	 *     Arr::is_array(array());
	 *     Arr::is_array(new ArrayObject);
	 *
	 *     // Returns FALSE
	 *     Arr::is_array(FALSE);
	 *     Arr::is_array('not an array!');
	 *     Arr::is_array(Database::instance());
	 *
	 * @param   mixed    value to check
	 * @return  boolean
	 */
	public static function is_array($value)
	{
		if (is_array($value))
		{
			// Definitely an array
			return TRUE;
		}
		else
		{
			// Possibly a Traversable object, functionally the same as an array
			return (is_object($value) AND $value instanceof Traversable);
		}
	}

	/**
	 * Gets a value from an array using a dot separated path.
	 *
	 *     // Get the value of $array['foo']['bar']
	 *     $value = Arr::path($array, 'foo.bar');
	 *
	 * Using a wildcard "*" will search intermediate arrays and return an array.
	 *
	 *     // Get the values of "color" in theme
	 *     $colors = Arr::path($array, 'theme.*.color');
	 *
	 *     // Using an array of keys
	 *     $colors = Arr::path($array, array('theme', '*', 'color'));
	 *
	 * @param   array   array to search
	 * @param   mixed   key path string (delimiter separated) or array of keys
	 * @param   mixed   default value if the path is not set
	 * @param   string  key path delimiter
	 * @return  mixed
	 */
	public static function path($array, $path, $default = NULL, $delimiter = NULL)
	{
		if ( ! Arr::is_array($array))
		{
			// This is not an array!
			return $default;
		}

		if (is_array($path))
		{
			// The path has already been separated into keys
			$keys = $path;
		}
		else
		{
			if (array_key_exists($path, $array))
			{
				// No need to do extra processing
				return $array[$path];
			}

			if ($delimiter === NULL)
			{
				// Use the default delimiter
				$delimiter = Arr::$delimiter;
			}

			// Remove starting delimiters and spaces
			$path = ltrim($path, "{$delimiter} ");

			// Remove ending delimiters, spaces, and wildcards
			$path = rtrim($path, "{$delimiter} *");

			// Split the keys by delimiter
			$keys = explode($delimiter, $path);
		}

		do
		{
			$key = array_shift($keys);

			if (ctype_digit($key))
			{
				// Make the key an integer
				$key = (int) $key;
			}

			if (isset($array[$key]))
			{
				if ($keys)
				{
					if (Arr::is_array($array[$key]))
					{
						// Dig down into the next part of the path
						$array = $array[$key];
					}
					else
					{
						// Unable to dig deeper
						break;
					}
				}
				else
				{
					// Found the path requested
					return $array[$key];
				}
			}
			elseif ($key === '*')
			{
				// Handle wildcards

				$values = array();
				foreach ($array as $arr)
				{
					if ($value = Arr::path($arr, implode('.', $keys)))
					{
						$values[] = $value;
					}
				}

				if ($values)
				{
					// Found the values requested
					return $values;
				}
				else
				{
					// Unable to dig deeper
					break;
				}
			}
			else
			{
				// Unable to dig deeper
				break;
			}
		}
		while ($keys);

		// Unable to find the value requested
		return $default;
	}

	/**
	* Set a value on an array by path.
	*
	* @see Arr::path()
	* @param array   $array     Array to update
	* @param string  $path      Path
	* @param mixed   $value     Value to set
	* @param string  $delimiter Path delimiter
	*/
	public static function set_path( & $array, $path, $value, $delimiter = NULL)
	{
		if ( ! $delimiter)
		{
			// Use the default delimiter
			$delimiter = Arr::$delimiter;
		}

		// Split the keys by delimiter
		$keys = explode($delimiter, $path);

		// Set current $array to inner-most array path
		while (count($keys) > 1)
		{
			$key = array_shift($keys);

			if (ctype_digit($key))
			{
				// Make the key an integer
				$key = (int) $key;
			}

			if ( ! isset($array[$key]))
			{
				$array[$key] = array();
			}

			$array = & $array[$key];
		}

		// Set key on inner-most array
		$array[array_shift($keys)] = $value;
	}

	/**
	 * Fill an array with a range of numbers.
	 *
	 *     // Fill an array with values 5, 10, 15, 20
	 *     $values = Arr::range(5, 20);
	 *
	 * @param   integer  stepping
	 * @param   integer  ending number
	 * @return  array
	 */
	public static function range($step = 10, $max = 100)
	{
		if ($step < 1)
			return array();

		$array = array();
		for ($i = $step; $i <= $max; $i += $step)
		{
			$array[$i] = $i;
		}

		return $array;
	}

	/**
	 * Retrieve a single key from an array. If the key does not exist in the
	 * array, the default value will be returned instead.
	 *
	 *     // Get the value "username" from $_POST, if it exists
	 *     $username = Arr::get($_POST, 'username');
	 *
	 *     // Get the value "sorting" from $_GET, if it exists
	 *     $sorting = Arr::get($_GET, 'sorting');
	 *
	 * @param   array   array to extract from
	 * @param   string  key name
	 * @param   mixed   default value
	 * @return  mixed
	 */
	public static function get($array, $key, $default = NULL)
	{
		return isset($array[$key]) ? $array[$key] : $default;
	}

	/**
	 * Retrieves multiple keys from an array. If the key does not exist in the
	 * array, the default value will be added instead.
	 *
	 *     // Get the values "username", "password" from $_POST
	 *     $auth = Arr::extract($_POST, array('username', 'password'));
	 *
	 * @param   array   array to extract keys from
	 * @param   array   list of key names
	 * @param   mixed   default value
	 * @return  array
	 */
	public static function extract($array, array $keys, $default = NULL)
	{
		$found = array();
		foreach ($keys as $key)
		{
			$found[$key] = isset($array[$key]) ? $array[$key] : $default;
		}

		return $found;
	}

	/**
	 * Retrieves muliple single-key values from a list of arrays.
	 *
	 *     // Get all of the "id" values from a result
	 *     $ids = Arr::pluck($result, 'id');
	 *
	 * [!!] A list of arrays is an array that contains arrays, eg: array(array $a, array $b, array $c, ...)
	 *
	 * @param   array   list of arrays to check
	 * @param   string  key to pluck
	 * @return  array
	 */
	public static function pluck($array, $key)
	{
		$values = array();

		foreach ($array as $row)
		{
			if (isset($row[$key]))
			{
				// Found a value in this row
				$values[] = $row[$key];
			}
		}

		return $values;
	}

	/**
	 * Adds a value to the beginning of an associative array.
	 *
	 *     // Add an empty value to the start of a select list
	 *     Arr::unshift($array, 'none', 'Select a value');
	 *
	 * @param   array   array to modify
	 * @param   string  array key name
	 * @param   mixed   array value
	 * @return  array
	 */
	public static function unshift( array & $array, $key, $val)
	{
		$array = array_reverse($array, TRUE);
		$array[$key] = $val;
		$array = array_reverse($array, TRUE);

		return $array;
	}

	/**
	 * Recursive version of [array_map](http://php.net/array_map), applies the
	 * same callback to all elements in an array, including sub-arrays.
	 *
	 *     // Apply "strip_tags" to every element in the array
	 *     $array = Arr::map('strip_tags', $array);
	 *
	 * [!!] Unlike `array_map`, this method requires a callback and will only map
	 * a single array.
	 *
	 * @param   mixed   callback applied to every element in the array
	 * @param   array   array to map
	 * @param   array   array of keys to apply to
	 * @return  array
	 */
	public static function map($callback, $array, $keys = NULL)
	{
		foreach ($array as $key => $val)
		{
			if (is_array($val))
			{
				$array[$key] = Arr::map($callback, $array[$key]);
			}
			elseif ( ! is_array($keys) or in_array($key, $keys))
			{
				if (is_array($callback))
				{
					foreach ($callback as $cb)
					{
						$array[$key] = call_user_func($cb, $array[$key]);
					}
				}
				else
				{
					$array[$key] = call_user_func($callback, $array[$key]);
				}
			}
		}

		return $array;
	}

	/**
	 * Merges one or more arrays recursively and preserves all keys.
	 * Note that this does not work the same as [array_merge_recursive](http://php.net/array_merge_recursive)!
	 *
	 *     $john = array('name' => 'john', 'children' => array('fred', 'paul', 'sally', 'jane'));
	 *     $mary = array('name' => 'mary', 'children' => array('jane'));
	 *
	 *     // John and Mary are married, merge them together
	 *     $john = Arr::merge($john, $mary);
	 *
	 *     // The output of $john will now be:
	 *     array('name' => 'mary', 'children' => array('fred', 'paul', 'sally', 'jane'))
	 *
	 * @param   array  initial array
	 * @param   array  array to merge
	 * @param   array  ...
	 * @return  array
	 */
	public static function merge(array $a1, array $a2)
	{
		$result = array();
		for ($i = 0, $total = func_num_args(); $i < $total; $i++)
		{
			// Get the next array
			$arr = func_get_arg($i);

			// Is the array associative?
			$assoc = Arr::is_assoc($arr);

			foreach ($arr as $key => $val)
			{
				if (isset($result[$key]))
				{
					if (is_array($val) AND is_array($result[$key]))
					{
						if (Arr::is_assoc($val))
						{
							// Associative arrays are merged recursively
							$result[$key] = Arr::merge($result[$key], $val);
						}
						else
						{
							// Find the values that are not already present
							$diff = array_diff($val, $result[$key]);

							// Indexed arrays are merged to prevent duplicates
							$result[$key] = array_merge($result[$key], $diff);
						}
					}
					else
					{
						if ($assoc)
						{
							// Associative values are replaced
							$result[$key] = $val;
						}
						elseif ( ! in_array($val, $result, TRUE))
						{
							// Indexed values are added only if they do not yet exist
							$result[] = $val;
						}
					}
				}
				else
				{
					// New values are added
					$result[$key] = $val;
				}
			}
		}

		return $result;
	}

	/**
	 * Overwrites an array with values from input arrays.
	 * Keys that do not exist in the first array will not be added!
	 *
	 *     $a1 = array('name' => 'john', 'mood' => 'happy', 'food' => 'bacon');
	 *     $a2 = array('name' => 'jack', 'food' => 'tacos', 'drink' => 'beer');
	 *
	 *     // Overwrite the values of $a1 with $a2
	 *     $array = Arr::overwrite($a1, $a2);
	 *
	 *     // The output of $array will now be:
	 *     array('name' => 'jack', 'mood' => 'happy', 'food' => 'tacos')
	 *
	 * @param   array   master array
	 * @param   array   input arrays that will overwrite existing values
	 * @return  array
	 */
	public static function overwrite($array1, $array2)
	{
		foreach (array_intersect_key($array2, $array1) as $key => $value)
		{
			$array1[$key] = $value;
		}

		if (func_num_args() > 2)
		{
			foreach (array_slice(func_get_args(), 2) as $array2)
			{
				foreach (array_intersect_key($array2, $array1) as $key => $value)
				{
					$array1[$key] = $value;
				}
			}
		}

		return $array1;
	}

	/**
	 * Creates a callable function and parameter list from a string representation.
	 * Note that this function does not validate the callback string.
	 *
	 *     // Get the callback function and parameters
	 *     list($func, $params) = Arr::callback('Foo::bar(apple,orange)');
	 *
	 *     // Get the result of the callback
	 *     $result = call_user_func_array($func, $params);
	 *
	 * @param   string  callback string
	 * @return  array   function, params
	 */
	public static function callback($str)
	{
		// Overloaded as parts are found
		$command = $params = NULL;

		// command[param,param]
		if (preg_match('/^([^\(]*+)\((.*)\)$/', $str, $match))
		{
			// command
			$command = $match[1];

			if ($match[2] !== '')
			{
				// param,param
				$params = preg_split('/(?<!\\\\),/', $match[2]);
				$params = str_replace('\,', ',', $params);
			}
		}
		else
		{
			// command
			$command = $str;
		}

		if (strpos($command, '::') !== FALSE)
		{
			// Create a static method callable command
			$command = explode('::', $command, 2);
		}

		return array($command, $params);
	}

	/**
	 * Convert a multi-dimensional array into a single-dimensional array.
	 *
	 *     $array = array('set' => array('one' => 'something'), 'two' => 'other');
	 *
	 *     // Flatten the array
	 *     $array = Arr::flatten($array);
	 *
	 *     // The array will now be
	 *     array('one' => 'something', 'two' => 'other');
	 *
	 * [!!] The keys of array values will be discarded.
	 *
	 * @param   array   array to flatten
	 * @return  array
	 * @since   3.0.6
	 */
	public static function flatten($array)
	{
		$flat = array();
		foreach ($array as $key => $value)
		{
			if (is_array($value))
			{
				$flat += Arr::flatten($value);
			}
			else
			{
				$flat[$key] = $value;
			}
		}
		return $flat;
	}
}


// ----------------------------------------------------------------
//   global function
// ----------------------------------------------------------------


/**
 * Load all functions from the helper file
 *
 * syntax:
 * use_helper('Cookie');
 * use_helper('Number', 'Javascript', 'Cookie', ...);
 *
 * @param  string helpers in CamelCase
 * @return void
 */
function use_helper()
{
    static $_helpers = array();
    
    $helpers = func_get_args();
    
    foreach ($helpers as $helper) {
        if (in_array($helper, $_helpers)) continue;
        
        $helper_file = HELPER_PATH.DIRECTORY_SEPARATOR.$helper.'.php';
        
        if ( ! file_exists($helper_file)) {
            throw new Exception("Helper file '{$helper}' not found!");
        }
        
        include $helper_file;
        $_helpers[] = $helper;
    }
}



/**
 * Load model class from the model file (faster than waiting for the __autoload function)
 *
 * syntax:
 * use_model('Blog');
 * use_model('Post', 'Category', 'Tag', ...);
 *
 * @param  string models in CamelCase
 * @return void
 */
function use_model()
{
    static $_models = array();
    
    $models = func_get_args();
    
    foreach ($models as $model) {
        if (in_array($model, $_models)) continue;
        
        $model_file = APP_PATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.$model.'.php';
        
        if ( ! file_exists($model_file)) {
            throw new Exception("Model file '{$model}' not found!");
        }
        
        include $model_file;
        $_models[] = $model;
    }
}



/**
 * Get the request method used to send this page
 *
 * @return string possible value: GET, POST or AJAX
 */
function get_request_method()
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') return 'AJAX';
    else if ( ! empty($_POST)) return 'POST';
    else return 'GET';
}




/**
 * Redirect this page to the url passed in param
 */
function redirect($url)
{
    header('Location: '.$url); exit;
}



/**
 * Encodes HTML safely for UTF-8. Use instead of htmlentities.
 */
function html_encode($string)
{
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
}



/**
 * Display a 404 page not found and exit
 */
function page_not_found()
{
    Observer::notify('page_not_found');
	
    echo new View('404');
    exit;
}



function convert_size($num)
{
    if ($num >= 1073741824) $num = round($num / 1073741824 * 100) / 100 .' gb';
    else if ($num >= 1048576) $num = round($num / 1048576 * 100) / 100 .' mb';
    else if ($num >= 1024) $num = round($num / 1024 * 100) / 100 .' kb';
    else $num .= ' b';
    return $num;
}



// Information about time and memory
function memory_usage()
{
    return convert_size(memory_get_usage());
}



function execution_time()
{
    return sprintf("%01.4f", get_microtime() - FRAMEWORK_STARTING_MICROTIME);
}



function get_microtime()
{
    $time = explode(' ', microtime());
    return doubleval($time[0]) + $time[1];
}



/**
 * Provides a nice print out of the stack trace when an exception is thrown.
 *
 * @param Exception $e Exception object.
 */
function framework_exception_handler($e)
{
	Observer::notify('framework_exception', $e);
	
    if (!DEBUG)
		page_not_found();
    
    echo '<style>h1,h2,h3,p,td {font-family:Verdana; font-weight:lighter;}</style>';
    echo '<p>Uncaught '.get_class($e).'</p>';
    echo '<h1>'.$e->getMessage().'</h1>';

    $traces = $e->getTrace();
    if (count($traces) > 1)
	{
        echo '<p><b>Trace in execution order:</b></p>'.
             '<pre style="font-family:Verdana; line-height: 20px">';
        
        $level = 0;
        foreach (array_reverse($traces) as $trace)
		{
            ++$level;

            if (isset($trace['class'])) echo $trace['class'].'&rarr;';
                
            $args = array();
            if ( ! empty($trace['args']) )
			{
                foreach( $trace['args'] as $arg )
				{
                    if (is_null($arg)) $args[] = 'null';
                    else if (is_array($arg)) $args[] = 'array['.sizeof($arg).']';
                    else if (is_object($arg)) $args[] = get_class($arg).' Object';
                    else if (is_bool($arg)) $args[] = $arg ? 'true' : 'false';
                    else if (is_int($arg)) $args[] = $arg;
                    else
					{
                        $arg = htmlspecialchars(substr($arg, 0, 64));
                        if (strlen($arg) >= 64) $arg .= '...';
                        $args[] = "'". $arg ."'";
                    }
                }
            }
			
            echo '<b>'.$trace['function'].'</b>('.implode(', ',$args).')  ';
            echo 'on line <code>'.(isset($trace['line']) ? $trace['line'] : 'unknown').'</code> ';
            echo 'in <code>'.(isset($trace['file']) ? $trace['file'] : 'unknown')."</code>\n";
            echo str_repeat("   ", $level);
        }
        echo '</pre>';
    }
    echo "<p>Exception was thrown on line <code>"
         . $e->getLine() . "</code> in <code>"
         . $e->getFile() . "</code></p>";
    
    $dispatcher_status = Dispatcher::getStatus();
    $dispatcher_status['request method'] = get_request_method();
    debug_table($dispatcher_status, 'Dispatcher status');
    if ( ! empty($_GET)) debug_table($_GET, 'GET');
    if ( ! empty($_POST)) debug_table($_POST, 'POST');
    if ( ! empty($_COOKIE)) debug_table($_COOKIE, 'COOKIE');
    debug_table($_SERVER, 'SERVER');
}

function debug_table($array, $label, $key_label='Variable', $value_label='Value')
{
    echo '<h2>'.$label.'</h2>';
    echo '<table cellpadding="3" cellspacing="0" style="width: 800px; border: 1px solid #ccc">';
    echo '<tr><td style="border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;">'.$key_label.'</td>'.
         '<td style="border-bottom: 1px solid #ccc;">'.$value_label.'</td></tr>';
    
    foreach ($array as $key => $value)
	{
        if( is_null($value) ) $value = 'null';
        else if( is_array($value) ) $value = 'array['.sizeof($value).']';
        else if( is_object($value) ) $value = get_class($value).' Object';
        else if( is_bool($value) ) $value = $value ? 'true' : 'false';
        else if( is_int($value) ) $value = $value;
        else
		{
            $value = htmlspecialchars(substr($value, 0, 64));
            if (strlen($value) >= 64) $value .= ' &hellip;';
        }
        echo '<tr><td><code>'.$key.'</code></td><td><code>'.$value.'</code></td></tr>';
    }
    echo '</table>';
}

set_exception_handler('framework_exception_handler');



/**
 * This function will strip slashes if magic quotes is enabled so 
 * all input data ($_GET, $_POST, $_COOKIE) is free of slashes
 */
if( get_magic_quotes_gpc() )
{
    function stripslashes_gpc( &$value, &$key )
    {
        $value = stripslashes($value);
		$key = stripslashes($key);
    }
	
    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}