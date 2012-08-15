<?php

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
 * @package install
 *
 * @author Maslakov Alexander <jmas.ukraine@gmail.com>
 * @version 0.1.0
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

// Defines
define('CMS_ROOT',  realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'));
define('I18N_PATH', CMS_ROOT.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'i18n');
define('CONFIG_FILE_PATH', CMS_ROOT.DIRECTORY_SEPARATOR.'config.php');
define('CONFIGTPL_FILE_PATH', CMS_ROOT.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'config.tpl');
define('SQLDUMP_FILE_PATH', CMS_ROOT.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'dump.sql');


// Timezone default
define('DEFAULT_TIMEZONE', 'Europe/Helsinki');


// Timezone
ini_set('date.timezone', DEFAULT_TIMEZONE);
if(function_exists('date_default_timezone_set'))
    date_default_timezone_set(DEFAULT_TIMEZONE);
else
    putenv('TZ='.DEFAULT_TIMEZONE);


// Check config.tpl file
if (!file_exists(CONFIGTPL_FILE_PATH))
	die('File config.tpl not exists! This file is required!');

	
// Check config.php
if (file_exists(CONFIG_FILE_PATH))
	die('System already installed! Please, remove this directory!');


// Requires
require_once(CMS_ROOT.DIRECTORY_SEPARATOR.'cms'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'I18n.php');


// i18n settings
$default_lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
$i18n_lang = isset($_GET['lang']) ? htmlentities(strtolower($_GET['lang'])): $default_lang;
I18n::setLocale($i18n_lang);


// Data
$data = array();


// Success/Error
$success = false;
$error   = false;


// Get $langs
$langs = scandir(I18N_PATH);


// Requirements
$req_pdo     = class_exists('PDO');

$pdo_drv = ($req_pdo ? PDO::getAvailableDrivers(): array());

$req_mysql   = in_array('mysql', $pdo_drv);
$req_pgsql   = in_array('pgsql', $pdo_drv);
$req_sqlite  = in_array('sqlite', $pdo_drv);
$req_php     = (PHP_VERSION < 5 ? false: true);
$req_json    = (function_exists('json_encode') || class_exists('JSON'));
$req_rewrite = (isset($_GET['mod_rewrite']) && $_GET['mod_rewrite'] == '1' ? true: false);


// POST
if (!empty($_POST['install']))
{
	$data = $_POST['install'];
	
	if (!$req_php)
	{
		$error = __('Require support of PHP 5+!');
	}
	else if (!$req_pdo)
	{
		$error = __('Require support of PDO extension!');
	}
	else if (!$req_mysql && !$req_pgsql && !$req_sqlite)
	{
		$error = __('Require support of PDO driver MySQL or PostgreSQL or SQLite!');
	}
	else if (($data['db_driver'] == 'mysql' || $data['db_driver'] == 'pgsql') && (empty($data['db_server']) || empty($data['db_user']) || empty($data['db_name'])))
	{
		$error = __('Fields <b>Database driver</b>, <b>Database server</b>, <b>Database user</b>, <b>Database name</b> are required!');
	}
	else if ($data['db_driver'] == 'sqlite' && empty($data['db_name']))
	{
		$error = __('Field <b>Database name</b> is required!');
	}
	else if (empty($data['username']))
	{
		$error = __('Field <b>Administrator username</b> is required!');
	}
	else
	{
		// SQLite needs more than 30 seconds
		@set_time_limit(180);
		
		// Prepare connection
		if ($data['db_driver'] != 'sqlite' )
			$db_dsn = $data['db_driver'] . ':dbname='. $data['db_name'] . (';host=' . $data['db_server'] . (!empty($data['db_port']) ? ';port=' . $data['db_port']: ''));
		else
			$db_dsn = $data['db_driver'] . ':'. $data['db_name'];
		
		$db_exception = null;
		
		$connection = false;
		
		try
		{
			$connection = new PDO( $db_dsn, ($data['db_driver'] != 'sqlite' ? $data['db_user']: null), ($data['db_driver'] != 'sqlite' ? $data['db_password']: null) );
			$connection->exec('SET NAMES "utf8"');
			$connection->exec('SET time_zone = "'. date_default_timezone_get() .'"');
		}
		catch (Exception $e) { $db_exception = $e->getMessage(); }
		
		if (!$connection)
		{
			$error = __('Can\'t connect to Database! :message', array(':message' => $db_exception));
		}
		else
		{
			$schema_file = CMS_ROOT.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'schema_'.$data['db_driver'].'.sql';
			$dump_file = CMS_ROOT.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'dump.sql';
			
			if (!file_exists($schema_file))
			{
				$error = __('Database schema file not found!');
			}
			else
			{
				// Create tables
				
				$schema_content = file_get_contents($schema_file);
				$schema_content = str_replace('TABLE_PREFIX_', $data['table_prefix'], $schema_content);
				$schema_content = preg_split('/;(\s*)$/m', $schema_content);
				
				$schema_error = false;
				
				foreach ($schema_content as $create_table_sql)
				{
					$create_table_sql = trim($create_table_sql);
					
					if (!empty($create_table_sql))
					{
						if ($connection->exec($create_table_sql) === false)
						{
							$schema_error = true;
							break;
						}
					}
				}
				
				if ($schema_error)
				{
					$e_info = $connection->errorInfo();
					$error = __('Problems with creating Database schema! :message',  array(':message' => $e_info[2]));
				}
				else if (!file_exists($dump_file))
				{
					$error = __('Database dump file not found!');
				}
				else
				{
					// Insert SQL dump
					
					$password = '12'.dechex(rand(100000000, 4294967295)).'K';
					
					function date_incremenator()
					{
						static $cpt=1;
						$cpt++;
						return date('Y-m-d H:i:s', time()+$cpt);
					}
					
					$dump_content = file_get_contents($dump_file);
					$dump_content = str_replace('TABLE_PREFIX_', $data['table_prefix'], $dump_content);
					$dump_content = str_replace('__ADMIN_PASSWORD__', sha1($password), $dump_content);
					$dump_content = preg_replace_callback('/__DATE__/m', 'date_incremenator', $dump_content);
					$dump_content = str_replace('__LANG__', $i18n_lang, $dump_content);
					$dump_content = preg_split('/;(\s*)$/m', $dump_content);
					
					$dump_error = false;
				
					foreach ($dump_content as $insert_sql)
					{
						$insert_sql = trim($insert_sql);
						
						if (!empty($insert_sql))
						{
							if ($connection->exec($insert_sql) === false)
							{
								$dump_error = true;
								break;
							}
						}
					}
					
					if ($dump_error)
					{
						$e_info = $connection->errorInfo();
						$error = __('Problems with importing Database dump! :message', array(':message' => $e_info[2]));
					}
					else
					{
						// Insert settings to config.php
						
						$tpl_content = file_get_contents(CONFIGTPL_FILE_PATH);
						
						$repl = array(
							'__DB_DSN__'          => $db_dsn,
							'__DB_USER__'         => $data['db_user'],
							'__DB_PASS__'         => $data['db_password'],
							'__TABLE_PREFIX__'    => $data['table_prefix'],
							'__USE_MOD_REWRITE__' => ($req_rewrite ? 'true': 'false'),
							'__URL_SUFFIX__'      => $data['url_suffix'],
							'__LANG__'            => $i18n_lang
						);
						
						$tpl_content = str_replace(
							array_keys($repl),
							array_values($repl),
							$tpl_content
						);
						
						if (file_put_contents(CONFIG_FILE_PATH, $tpl_content) !== false)
						{
							$success = true;
						}
						else
						{
							$error = __('Can\'t write config.php file!');
						}
					}
				}
			}
		}
	}
} // POST

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title><?php echo __('Installation'); ?> &ndash; Flexo CMS</title>
		
		<link href="install.css" rel="stylesheet" type="text/css" charset="utf-8" />
		
		<script src="../admin/javascripts/jquery-1.6.4.js"></script>
		
		<script>
			$(function()
			{
				// Messages
				$('.message').animate({top: 0}, 1000);
				
				
				// Langs
				$('#installLangField').bind('change', function()
				{
					location.href = '?lang=' + $(this).val();
				});
				
				
				// First field focus
				$('form:first input[type="text"]:first').focus();
				
				
				// DB variants				
				$('#installDriverField').bind('change', function()
				{
					var val = $(this).val();
					
					switch (val)
					{
						case 'sqlite':
							$('#installDBServer, #installDBPort, #installDBUser, #installDBPassword, #installDBPrefix').slideUp('fast');
							$('#installDBNameDescr').hide();
							$('#installDBNameSQLiteDescr').css('display', 'block').show();
							break;
						default:
							$('#installDBServer, #installDBPort, #installDBUser, #installDBPassword, #installDBPrefix').slideDown('fast');
							$('#installDBNameDescr').show();
							$('#installDBNameSQLiteDescr').hide();
							break;
					}
				});
			});
			
			// IE HTML5 hack (If you like to work with IE - you have a big problems ^___^ )
			if (document.all)
			{
				var e = ['header', 'nav', 'aside', 'article', 'section', 'footer', 'figure', 'hgroup', 'mark', 'output', 'time'];
				for (i in e) document.createElement(e[i]);
			}
		</script>
	</head>
	<body>
	
		<?php if ($error): ?><div id="errorMessage" class="message"><?php echo $error; ?></div><?php endif; ?>
		
		<div id="install" class="box">
			<h1 class="box-title"><?php echo __('Installation'); ?> &ndash; Flexo CMS</h1>
			
			<?php if (!$success): ?>
			
			<div id="installLang">
				<select id="installLangField" name="lang">
					<option value="en" >English</option>
					<?php foreach ($langs as $lang): if (substr($lang, -4) != '.php') continue; $lang = substr($lang, 0, 2); ?>
					<option value="<?php echo $lang; ?>" <?php if($i18n_lang == $lang) echo('selected'); ?> ><?php echo (isset(I18n::$locale_names[$lang]) ? I18n::$locale_names[$lang]: 'unknown'); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			
			<form id="installForm" action="?lang=<?php echo $i18n_lang; ?>" class="form form-<?php echo (isset($data['db_driver']) ? $data['db_driver']: 'mysql'); ?>" method="post">
				
				<ul id="requirements">
					<li class="<?php echo($req_php ? 'ok': 'bad'); ?>">PHP 5+ <?php echo __(!$req_php ? 'not supported': 'supported'); ?></li>
					<li class="<?php echo($req_pdo ? 'ok': 'bad'); ?>">PDO <?php echo __(!$req_pdo ? 'not supported': 'supported'); ?></li>
					<li class="<?php echo($req_mysql ? 'ok': 'bad'); ?>">PDO MySQL <?php echo __(!$req_mysql ? 'not supported': 'supported'); ?></li>
					<?php /* <li class="<?php echo($req_pgsql ? 'ok': 'bad'); ?>">PDO PostgreSQL <?php echo __(!$req_pgsql ? 'not supported': 'supported'); ?> (<?php echo __('optional'); ?>)</li> */ ?>
					<li class="<?php echo($req_sqlite ? 'ok': 'bad'); ?>">PDO SQLite <?php echo __(!$req_sqlite ? 'not supported': 'supported'); ?> (<?php echo __('optional'); ?>)</li>
					<li class="<?php echo($req_json ? 'ok': 'bad'); ?>">JSON  <?php echo __(!$req_json ? 'not supported': 'supported'); ?> (<?php echo __('optional'); ?>)</li>
					<li class="<?php echo($req_rewrite ? 'ok': 'bad'); ?>">mod_rewrite <?php echo __(!$req_rewrite ? 'not supported': 'supported'); ?> (<?php echo __('optional'); ?>)</li>
				</ul>
				
				<?php if(!$req_php || !$req_pdo || !$req_rewrite): ?>
				<p><a href="#"><?php echo __('If some requirements not aviable&hellip;'); ?></a></p>
				<?php endif; ?>
				
				<h2 class="box-header"><?php echo __('Database information'); ?></h2>
				
				<section id="installDriver">
					<label for="installDriverField"><?php echo __('Database driver'); ?> <em><?php echo __('Required. PDO support and the SQLite 3 plugin are required to use SQLite 3.'); ?></em></label>
					<span>
						<select id="installDriverField" name="install[db_driver]">
							<option value="mysql" <?php if (isset($data['db_driver']) && $data['db_driver'] == 'mysql') echo('selected'); ?> >MySQL</option>
							<?php if($req_pgsql): ?>
							<option value="pgsql" <?php if (isset($data['db_driver']) && $data['db_driver'] == 'pgsql') echo('selected'); ?> >PostgreSQL</option>
							<?php endif; ?>
							<?php if($req_sqlite): ?>
							<option value="sqlite" <?php if (isset($data['db_driver']) && $data['db_driver'] == 'sqlite') echo('selected'); ?> >SQLite</option>
							<?php endif; ?>
						</select>
					</span>
				</section>
				
				<section id="installDBServer">
					<label for="installDBServerField"><?php echo __('Database server'); ?> <em><?php echo __('Required.'); ?></em></label>
					<span><input id="installDBServerField" class="input-text" type="text" name="install[db_server]" maxlength="255" size="50" value="<?php echo(isset($data['db_server']) ? $data['db_server']: 'localhost'); ?>" /></span>
				</section>
				
				<section id="installDBPort">
					<label for="installDBPortField"><?php echo __('Database port'); ?> <em><?php echo __('Optional. Default: 3306'); ?></em></label>
					<span><input id="installDBPortField" class="input-text" type="text" name="install[db_port]" maxlength="255" size="50" value="<?php echo(isset($data['db_port']) ? $data['db_port']: '3306'); ?>" /></span>
				</section>
				
				<section id="installDBUser">
					<label for="installDBUserField"><?php echo __('Database user'); ?> <em><?php echo __('Required.'); ?></em></label>
					<span><input id="installDBUserField" class="input-text" type="text" name="install[db_user]" maxlength="255" size="50" value="<?php echo(isset($data['db_user']) ? $data['db_user']: 'root'); ?>" /></span>
				</section>
				
				<section id="installDBPassword">
					<label for="installDBPasswordField"><?php echo __('Database password'); ?> <em><?php echo __('Optional. If there is no database password, leave it blank.'); ?></em></label>
					<span><input id="installDBPasswordField" class="input-text" type="text" name="install[db_password]" maxlength="255" size="50" value="<?php echo(isset($data['db_password']) ? $data['db_password']: ''); ?>" /></span>
				</section>
				
				<section id="installDBName">
					<label for="installDBNameField"><?php echo __('Database name'); ?> <em id="installDBNameDescr"><?php echo __('Required. You have to create a database manually and enter its name here.'); ?></em><em id="installDBNameSQLiteDescr"><?php echo __('Required. Enter the <b>absolute</b> path to the database file.'); ?></em></label>
					<span><input id="installDBNameField" class="input-text" type="text" name="install[db_name]" maxlength="255" size="50" value="<?php echo(isset($data['db_name']) ? $data['db_name']: ''); ?>" /></span>
				</section>
				
				<section id="installDBPrefix">
					<label for="installDBPrefixField"><?php echo __('Prefix'); ?> <em><?php echo __('Optional. Usefull to prevent conflicts if you have, or plan to have, multiple Flexo installations with a single database.'); ?></em></label>
					<span><input id="installDBPrefixField" class="input-text" type="text" name="install[table_prefix]" maxlength="255" size="50" value="<?php echo(isset($data['table_prefix']) ? $data['table_prefix']: ''); ?>" /></span>
				</section>
				
				<h2 class="box-header"><?php echo __('Other information'); ?></h2>
				
				<section id="installUsername">
					<label for="installUsernameField"><?php echo __('Administrator username'); ?> <em><?php echo __('Required. Allows you to specify a custom username for the administrator. Default: admin'); ?></em></label>
					<span><input id="installUsernameField" class="input-text" type="text" name="install[username]" maxlength="255" size="50" value="<?php echo(isset($data['username']) ? $data['username']: 'admin'); ?>" /></span>
				</section>
				
				<section id="installURLSuffix">
					<label for="installURLSuffixField"><?php echo __('URL suffix'); ?> <em><?php echo __('Optional. Add a suffix to simulate static html files.'); ?></em></label>
					<span><input id="installURLSuffixField" class="input-text" type="text" name="install[url_suffix]" maxlength="255" size="50" value="<?php echo(isset($data['url_suffix']) ? $data['url_suffix']: '.html'); ?>" /></span>
				</section>
				
				<div class="box-buttons">
					<button><img src="../admin/images/check.png" /> <?php echo __('Install now!'); ?></button>
				</div>
				
			</form><!--/#installForm-->
			
			<?php else: ?>
				<h2 id="installFinishHeader"><img src="../admin/images/check.png" /> <?php echo __('Congratulations! Flexo CMS is installed!'); ?></h2>
				
				<h3><?php echo __('You should now:'); ?></h3>
				
				<ol>
					<li><?php echo __('delete the <em>install/</em> folder!'); ?></li>
					<li><?php echo __('remove all write permissions from the <em>config.php</em> file!'); ?></li>
					<li><?php echo __('delete directory <em>readme/</em> to enhance security.'); ?></li>
				</ol>
				
				<div id="installInfo">
					<p><a href="../admin/"><?php echo __('Administration login page'); ?></a></p>
					<ul>
						<li><b><?php echo __('Username:'); ?></b> <?php echo (isset($data['username']) ? $data['username']: 'n/a'); ?></li>
						<li><b><?php echo __('Password:'); ?></b> <?php echo (isset($password) ? $password: 'n/a'); ?></li>
					</ul>
					<p class="note"><?php echo __('Please be aware: the password is generated by Flexo, please use it to login to Flexo and <strong>change your password</strong>!'); ?></p>
				</div>
			<?php endif; ?>
			
		</div><!--/#install-->
		
	</body>
</html>