#!/usr/bin/php -q
<?php
/*

A PHP SCRIPT TO UPGRADE FROM KOHANA 3.2.X TO KOHANA 3.3.X
------------------------------------------------------

Based on the works of :  
=> Daan (http://stackoverflow.com/users/987864/daan)
http://stackoverflow.com/questions/13935621/how-to-upgrade-from-kohana-3-2-to-3-3-implementing-psr-0  
=> Alex Cartwright <alexc223@gmail.com>
https://github.com/AlexC/kohana-upgrade-script

This script is designed to do bulk changes to your codebase that can
easily be automated, changes that would otherwise have the potential
to take a very long time for a large project. It does not provide a
fully automated migration, and it is highly recommended that you read
the official Kohana 3.3 upgrade guide before running this script.
http://kohanaframework.org/3.3/guide/kohana/upgrading

------------------------------------------------------

Be smart, be safe :
BACKUP YOUR WEBSITE BEFORE RUNNING THIS SCRIPT
------------------------------------------------------

This script handles :
- Changes in Boostrap / database config file / auth config file
- PSR-0 support (file/class naming conventions)
	- Change the file names 
	- Change the name of class in all files  (including calls, extends...)
- Case sensitive ORM, HTTP, URL, UTF8, HTML classes
- New syntax for Browser cache checking
- New syntax for Redirects (HTTP 300, 301, 302, 303, 307)
- Apply the changes listed above in Modules too

This script does not handle :
- HTTP Exceptions
- Custom Error Pages (HTTP 500, 404, 403, 401 etc)
- Query Builder Identifier Escaping
- Route Filters

Things to do :
- improve regex to prevent replacement mistakes between class and functions

HOW TO USE
------------------------------------------------------

This script is to use on your local version of your website
Do not use on your production server (as you will experiment permission issues as your server is not supose to let you modify PHP files dynamically for security reasons)
Once your local site is updated and you test everything, upload files via FTP to your production server. 

1/ Backup your site

2/ Download kohana 3.3

3/ Manulay replace the following folders from Kohana 3.3 to the website to upgrade :
- system
- modules/auth
- modules/cache
- modules/codebench
- modules/database
- modules/image
- modules/minion
- modules/orm
- modules/unittest
- modules/userguide

4/ Edit the settings at the begining of the script 

5/ Run this file
   - if you use a Mac : the best way will be to open the php file with BBedit and chose in the '#!' menu "Run in Terminal"  
   - you can also open a Terminal, go to the script folder and type ./upgrade-kohana.php  
   - you can also just run the script from your local webserver http://localhost/website/upgrade-kohana.php but it's not recommended as you may have some permissions problem to modify the PHP files  

------------------------------------------------------

AUTHOR
------------------------------------------------------

@author Erwan Dupeux-Maire  
www.upyupy.fr  
www.bwat.fr  

*/


/*
---------------
SETTINGS
---------------
*/

$dir = __DIR__;

// START OF SETTINGS
// Path to kohana v3.2 website to update 
$path = $dir . DIRECTORY_SEPARATOR . 'cms' . DIRECTORY_SEPARATOR;
//$path = '/Library/WebServer/Documents/work/clubDA/httpdocs/';

// List of folders to process (at least $path.'application' )
// You sould not use this script to upgrade third party modules that already have a v3.3 version.


$modules = [
	'api', 'assets', 'auth', 'behavior', 'breadcrumbs', 'captcha', 'dashboard',
	'datasource', 'elfinder', 'email', 'email_queue', 'filesystem', 'installer',
	'kodicms', 'logs', 'navigation', 'page_parts', 'pages', 'pagination', 'plugins', 
	'reflinks', 'scheduler', 'search', 'sidebar', 'snippet', 'tags', 'update', 'users',
	'widget'
];

$plugins = [
	'archive', 'backup', 'disqus', 'hybrid', 'maintenance', 'messages', 'page_fields',
	'page_not_found', 'part_revision', 'redactor', 
	'skeleton', 'skeleton_dashboard_widget', 'skeleton_widget', 'userguide'
];

$dirs = [];

foreach ($modules as $module)
{
	$dirs[] = $path . 'modules' . DIRECTORY_SEPARATOR . $module;
}

foreach ($plugins as $module)
{
	$dirs[] = $path . 'plugins' . DIRECTORY_SEPARATOR . $module;
}

// Search and replace class name in "views" folders to update the case ?
// true => yes | false => no
$checkInViews = true;

// If there is some files you don't want to process, add them to this array
$skip = array('_notes','.DS_Store','lib-mail-phpmailer','lib-rtf');

// Special class rewrinting 
// sometimes, captialize is not enought
// Use this array to set special changes()
$specials = array (
	'ui' => 'UI',
	'wysiwyg' => 'WYSIWYG',
    'gd' => 'GD',
    'orm' => 'ORM',
    'db' => 'DB',
    'mysql' => 'MySQL',
    'pdo' => 'PDO',
    'html' => 'HTML',
    'url' => 'URL',
    'http' => 'HTTP',
    'utf8' => 'UTF8',
    'validurl' => 'ValidURL',
    'validcolor' => 'ValidColor',
    'userfuncarray' => 'UserFuncArray',
    'urlsite' => 'URLSite',
    'stripnullbytes' => 'StripNullBytes',
    'mddoincludeviews' => 'MDDoIncludeViews',
    'mddoimageurl' => 'MDDoImageURL',
    'mddobaseurl' => 'MDDoBaseURL',
    'ltrimdigits' => 'LtrimDigits',
    'gruberurl' => 'GruberURL',
    'explodelimit' => 'ExplodeLimit',
    'datespan' => 'DateSpan',
    'autolinkemails' => 'AutoLinkEmails',
    'arrcallback' => 'ArrCallback',
    'acl' => 'ACL',
	'kodicms' => 'KodiCMS',
	'sso' => 'SSO',
	'api' => 'API',
	'cli' => 'CLI'
);


// END OF SETTINGS

// global variable that will memorize models name
$models = array();

// Function to remove folders and files 
// @author : http://stackoverflow.com/users/1226894/baba
// http://stackoverflow.com/questions/9835492/move-all-files-and-folders-in-a-folder-to-another
function rrmdir($dir)
{
	if (is_dir($dir))
	{
		$files = scandir($dir);
		foreach ($files as $file)
			if ($file != "." && $file != "..")
				rrmdir("$dir/$file");
		rmdir($dir);
	}
	else if (file_exists($dir))
		unlink($dir);
}

// Function to Copy folders and files       
// @author : http://stackoverflow.com/users/1226894/baba
function rcopy($src, $dst)
{
	if (file_exists($dst))
		rrmdir($dst);
	if (is_dir($src))
	{
		mkdir($dst);
		$files = scandir($src);
		foreach ($files as $file)
			if ($file != "." && $file != "..")
				rcopy("$src/$file", "$dst/$file");
	} else if (file_exists($src))
		copy($src, $dst);
}

// Function to search and replace in a file (not case sensitive)
// @author : Erwan Dupeux-Maire
function replaceInFile($file, $search, $replace)
{
	if (!is_file($file))
	{
		echo $file . ' does not exist (replaceInFile)' . "<br />\n";
		return false;
	}
	$str = str_ireplace($search, $replace, file_get_contents($file));
	return file_put_contents($file, $str);
}

// Function to search and replace in a file with regex
// @author : Erwan Dupeux-Maire
function pregReplaceInFile($file, $search, $replace)
{
	if (!is_file($file))
	{
		echo $file . ' does not exist (pregReplaceInFile)' . "<br />\n";
		return false;
	}
	$str = preg_replace($search, $replace, file_get_contents($file));
	return file_put_contents($file, $str);
}

function rewritteFolderFiles($dir, $shortdir)
{
	global $path, $skip, $specials, $models;
    //echo $dir."<br />\n";
  	
  	if (!is_dir($dir))
	{
		echo $dir.' does not exist (rewritteFolderFiles)'."<br />\n";
		return array();
	}
	
  	
    // let's start the fun
    $ffs = scandir($dir);
    $arr = array();
    foreach($ffs as $ff){
        if($ff != '.' && $ff != '..' && !in_array($ff, $skip))
        {
    		//echo $dir.'/'.$ff."<br />\n";
            // Write classname
            $filename = str_replace(
            	'.php', 
            	'', 
            	trim($shortdir.'/'.$ff, '/')
            );
            
            $arrPath = explode('/', $filename);
            foreach($arrPath as $k => $v)
            {
            	if (isset($specials[strtolower($v)]))
            	{
	            	// in case u have some custom rewriting to do
            		$arrPath[$k] = $specials[strtolower($v)];
            	}
            	else
            	{
            		// just capitalize
            		$arrPath[$k] = ucfirst($v);
            	}
            }
            $classname = join('_',$arrPath);
      		//echo $classname."<br />\n";
      		
			
            if (is_file($dir.'/'.$ff) && strpos($ff, '.php')!==false)
            {
            	// this array will be use to find/replace all occurences of a class name
            	// Explanation and limits of this regex : see #NOTE1 at the end of this file.
            	$arr['/(\(|\s|\t|\.|\=|\!)('.$classname.')(\s)*(\(|\:\:|extends|implements|\{)/i'] = '$1'.$classname.'$3$4';
            	$arr['/(instanceof)(\s)*('.$classname.')/i'] = '$1$2'.$classname.'';
            	
            	if (stripos($dir, '/model') !== false)
            	{
            		// special correction for ORM
            		// will replace ORM::factory('news') by ORM::factory('News') 
            		$modelname = str_replace('Model_','',$classname);
    				$arr['/(ORM\:\:factory\()(\s)*(\\\')*(\")*('.$modelname.')(\")*(\\\')*(\s)*(\)|\,)/i'] = '$1$2$3$4'.$modelname.'$6$7$8$9';
    				// Save model name
    				$models[] = $modelname;
            	}
            	$key = str_replace('.php', '', $ff);
            	if (isset($specials[strtolower($key)]))
				{
					// in case u have some custom rewriting to do
					$nn = $specials[strtolower($key)].'.php';
				}
				else
				{
					// just capitalize
					$nn = ucfirst($ff);
				}
            	// rename file
            	rename($dir.'/'.$ff, $dir.'/'.$nn);
            }
            if(is_dir($dir.'/'.$ff)) 
            {
				if (isset($specials[strtolower($ff)]))
				{
					// in case u have some custom rewriting to do
					$nn = $specials[strtolower($ff)];
				}
				else
				{
					// just capitalize
					$nn = ucfirst($ff);
				}            
            	// rename folder
            	rename($dir.'/'.$ff, $dir.'/'.$nn);
            	
            	// merge without matching keys
            	$arr = array_merge($arr, rewritteFolderFiles($dir.'/'.$ff, trim($shortdir.'/'.$ff, '/')));
            }
        }
    }
    return $arr;
}

function replaceClassNameInFolder($dir, $arrSearch, $arrReplace)
{
	global $skip;

	if (!is_dir($dir))
	{
		echo $dir . ' does not exist (replaceClassNameInFolder)' . "<br />\n";
		return array();
	}

	$ffs = scandir($dir);
	$arr = array();
	foreach ($ffs as $ff)
	{
		if ($ff != '.' && $ff != '..' && !in_array($ff, $skip))
		{
			if (is_file($dir . DIRECTORY_SEPARATOR . $ff) && strpos($ff, EXT) !== false)
			{
				$arr[] = $ff;
				pregReplaceInFile(
						$dir . DIRECTORY_SEPARATOR . $ff, $arrSearch, $arrReplace
				);
			}
			if (is_dir($dir . DIRECTORY_SEPARATOR . $ff))
			{
				$arr[$ff] = replaceClassNameInFolder($dir . DIRECTORY_SEPARATOR . $ff, $arrSearch, $arrReplace);
			}
		}
	}
	return $arr;
}

$listOfRegexReplace = array();
foreach ($dirs as $dir)
{
	echo 'Processing '.$dir."......<br />\n";

	// #5 in /classes and its subdirectories
	// rename all php files to match the case sensitive standard PSR-0
	$dir .= '/classes';
	if (!is_dir($dir))
	{
		echo $dir.' does not exist'."<br />\n";
		continue;
	}
	// Recursive function to update file and folder files and get back the list of class name to change
	$listOfRegexReplace = array_merge(
		$listOfRegexReplace,
		rewritteFolderFiles($dir, $shortdir='')
	);
}


// #10 some class names that were used with only 1 capital, are now written in full capitals (Html, Url, Http, UTF8). 
// Add them to the list of class to rewrite
$listOfRegexReplace['/(Acl)(\:\:)/i'] = 'ACL$2';
$listOfRegexReplace['/(Html)(\:\:)/i'] = 'HTML$2';
$listOfRegexReplace['/(Url)(\:\:)/i'] = 'URL$2';
$listOfRegexReplace['/(Http)(\:\:)/i'] = 'HTTP$2';
$listOfRegexReplace['/(Utf8)(\:\:)/i'] = 'UTF8$2';
$listOfRegexReplace['/(Form)(\:\:)/i'] = 'Form$2';
$listOfRegexReplace['/(ui)(\:\:)/i'] = 'UI$2';
$listOfRegexReplace['/(kodicms)(\:\:)/i'] = 'KodiCMS$2';
$listOfRegexReplace['/(sso)(\:\:)/i'] = 'SSO$2';
$listOfRegexReplace['/(api)(\:\:)/i'] = 'API$2';
$listOfRegexReplace['/(cli)(\:\:)/i'] = 'CLI$2';

// #11 Replace orm::factory by ORM:factory etc....
$listOfRegexReplace['/(\s|\=|\()(Orm)(\:\:)/i'] = '$1ORM$3';

// #12 Update Redirects (HTTP 300, 301, 302, 303, 307)
// ->request->redirect becomes ->redirect
// Request::current()->redirect becomes HTTP::redirect
// Request::initial()->redirect becomes HTTP::redirect
$listOfRegexReplace['/(\->request)(\s)*(\->redirect)(\s)*/i'] = '->redirect';
$listOfRegexReplace['/(Request\:\:current\(\))(\s)*(\->redirect)/i'] = 'HTTP::redirect';
$listOfRegexReplace['/(Request\:\:initial\(\))(\s)*(\->redirect)/i'] = 'HTTP::redirect';

// #13 Browser cache checking (ETags)
// $this->response->check_cache becomes $this->check_cache
$listOfRegexReplace['/(\->response)(\s)*(\->check_cache)(\s)*/i'] = '->check_cache';

// If we want to harmonize the "OR" case
// $listOfRegexReplace['/ or die/i'] = ' OR die';


//echo '---DISPLAY ALL REPLACEMENT REGEX ---'."<br />\n";
//print_r($listOfRegexReplace);
//echo '------'."<br />\n";
//
//// #14 change all class names in php filesName in /classes
//echo '---PROCESSING CLASSES---'."<br />\n";
//echo '> Processing change class names '.$dir."<br />\n";
//foreach ($dirs as $dir)
//{
//	$dir .= '/classes';
//	echo 'Processing '.$dir."......<br />\n";
//	echo 'This step can be long.'."<br />\n";
//	replaceClassNameInFolder(
//		$dir,
//		array_keys($listOfRegexReplace),// arrSearch
//		$listOfRegexReplace // arrSearch
//	);
//}

//if ($checkInViews)
//{
//
//	// #15 change all class names in views files
//	echo '---PROCESSING VIEWS---'."<br />\n";
//	foreach ($dirs as $dir)
//	{
//		$dir .= '/views';
//		echo 'Processing '.$dir."......<br />\n";
//		echo 'This step can be long.'."<br />\n";
//		replaceClassNameInFolder(
//			$dir,
//			array_keys($listOfRegexReplace),// arrSearch
//			$listOfRegexReplace // arrSearch
//		);
//	}
//}


// #16 change model names in ORM relation (in $_has_many, $_belongs_to,.....)
//echo '---PROCESSING MODELS RELATIONSHIP SETTINGS---'."<br />\n";
//
//$modelReg = array();
//foreach ($models as $name)
//{
//	// Regex to search for : 'model' => 'modelname'
//	$modelReg['/(\'|\")(model)(\'|\")(\s)*(\=\>)(\s)*(\'|\")('.$name.')(\'|\")/i'] = '$1$2$3$4$5$6$7'.$name.'$9';
//}
//if (count($modelReg))
//{
//	foreach ($dirs as $dir)
//	{
//		$dir .= '/classes/Model';
//		echo 'Processing '.$dir."......<br />\n";
//	
//		replaceClassNameInFolder(
//			$dir,
//			array_keys($modelReg),// arrSearch
//			$modelReg // arrSearch
//		);
//	}
//}

// #17 Personnal test
//echo '---PROCESSING PERSONNAL TEST---'."<br />\n";
//
//$modelReg = array();
//foreach ($models as $name)
//{
//	$modelReg['/(\$\_modele\_name)(\s)*(\=)(\s)*(\'|\")('.$name.')(\'|\")/i'] = '$1$2$3$4$5'.$name.'$7';
//}
//if (count($modelReg))
//{
//	foreach ($dirs as $dir)
//	{
//		$dir .= '/classes/Controller/Backoffice';
//		echo 'Processing '.$dir."......<br />\n";
//	
//		replaceClassNameInFolder(
//			$dir,
//			array_keys($modelReg),// arrSearch
//			$modelReg // arrSearch
//		);
//	}
//}

/*
// #NOTE1 
// Explaination of the regex /(\(|\s|\t|\.)(a1)(\s)*(\(|\:\:|extends|implements|\{)/i
// Find classname in a file : 
// - check something that start with space, tab, dot or "("
// - followed by the name of the class (here "a1")
// - followed by one, several or no space
// - followed by "(" or ':' or "extends" or "implements" or "{")
// - not cas sensitive
//
// The limit for now : can not distinct class declaration vs function declaration
// In ne following example, function a1()... should not be replace..
// 
$example = ' class a1 extends a1 () {
	function a1() {
	
	} 
	$title=\'a1\';
	$comment = "You shoudl take the highway a1! a1 is the fastest.";
	a1::doIt();
	$b = new a1();
	$c = Helper_Example(a1::action());
// with no indent ?
a1();
	// aaaa1bbbb
	// a1bbbb
	// aaa1
}';
$example = preg_replace(
	array('/(\(|\s|\t|\.)(a1)(\s)*(\(|\:\:|extends|implements|\{)/i'), 
	array('$1A1$3$4'),
	$example
);
echo '<pre>'.($example).'</pre>';
// -----------------
// Result will be :
// -----------------
// class A1 extends A1 () {
// 	function A1() {
// 	
// 	} 
// 	$title='a1';
// 	$comment = "You shoudl take the highway a1! a1 is the fastest.";
// 	A1::doIt();
// 	$b = new A1();
// 	$c = Helper_Example(A1::action());
// // with no indent ?
// A1();
// 	// aaaa1bbbb
// 	// a1bbbb
// 	// aaa1
// }
*/
exit();