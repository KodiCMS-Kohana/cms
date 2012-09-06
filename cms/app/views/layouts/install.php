<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo Setting::get('admin_title'); ?></title>
		<?php
		echo HTML::style( ADMIN_URL . 'libs/bootstrap/css/bootstrap.css' ) . "\n";
		echo HTML::style( ADMIN_URL . 'stylesheets/backend.css' ) . "\n";
		echo HTML::style( ADMIN_URL . 'libs/jgrowl/jquery.jgrowl.css' ) . "\n";

		echo HTML::script( ADMIN_URL . 'libs/jquery-1.7.2.min.js' ) . "\n";
		echo HTML::script( ADMIN_URL . 'libs/bootstrap/js/bootstrap.min.js' ) . "\n";
		echo HTML::script( ADMIN_URL . 'libs/jgrowl/jquery.jgrowl_minimized.js' ) . "\n";
		?>

		<?php echo $messages; ?>
		
		<style type="text/css">
			table td.pass { color: #191; }
			table td.fail { color: #911; }

			#results.pass { background: #191; }
			#results.fail { background: #911; }
			
			.well {
				background: #fff;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1><?php echo __('Installation'); ?></h1>
			</div>

			<form action="<?php echo URL::site('install/go'); ?>" class="form-horizontal well" method="post">
				<fieldset>
					<legend><?php echo __('Database information'); ?></legend>

					<br />

					<?php echo Form::hidden('install[db_driver]', 'mysql'); ?>

					<div class="control-group">
						<label class="control-label" for="installDBServerField"><?php echo __('Database server'); ?></label>
						<div class="controls">
							<?php echo Form::input('install[db_server]', Arr::get($data, 'db_server', 'localhost'), array(
								'class' => 'input-xlarge', 'id' => 'installDBServerField'
							)); ?> <?php echo HTML::label(__('Required.')); ?>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="installDBUserField"><?php echo __('Database user'); ?></label>
						<div class="controls">
							<?php echo Form::input('install[db_user]', Arr::get($data, 'db_user', 'root'), array(
								'class' => 'input-xlarge', 'id' => 'installDBUserField'
							)); ?> <?php echo HTML::label(__('Required.')); ?>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="installDBPasswordField"><?php echo __('Database password'); ?></label>
						<div class="controls">
							<?php echo Form::password('install[db_password]', Arr::get($data, 'db_password'), array(
								'class' => 'input-xlarge', 'id' => 'installDBPasswordField'
							)); ?>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="installDBNameField"><?php echo __('Database name'); ?></label>
						<div class="controls">
							<?php echo Form::input('install[db_name]', Arr::get($data, 'db_name'), array(
								'class' => 'input-xlarge', 'id' => 'installDBNameField'
							)); ?> <?php echo HTML::label(__('Required.')); ?>

							<p class="help-block"><?php echo __('Required. You have to create a database manually and enter its name here.'); ?></p>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="installDBPrefixField"><?php echo __('Prefix'); ?></label>
						<div class="controls">
							<?php echo Form::input('install[table_prefix]', Arr::get($data, 'table_prefix'), array(
								'class' => 'input-xlarge', 'id' => 'installDBPrefixField'
							)); ?>

							<p class="help-block"><?php echo __('Optional. Usefull to prevent conflicts if you have, or plan to have, multiple Flexo installations with a single database.'); ?></p>
						</div>
					</div>
				</fieldset>

				<fieldset>
					<legend><?php echo __('Other information'); ?></legend>
					
					<div class="control-group">
						<label class="control-label" for="installSiteNameField"><?php echo __('Site name'); ?></label>
						<div class="controls">
							<?php echo Form::input('install[site_name]', Arr::get($data, 'site_name', 'Kohana frog CMS'), array(
								'class' => 'span6', 'id' => 'installSiteNameField'
							)); ?> <?php echo HTML::label(__('Required.')); ?>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="installUsernameField"><?php echo __('Administrator username'); ?></label>
						<div class="controls">
							<?php echo Form::input('install[username]', Arr::get($data, 'username', 'admin'), array(
								'class' => 'input-xlarge', 'id' => 'installUsernameField'
							)); ?> <?php echo HTML::label(__('Required.')); ?>

							<p class="help-block"><?php echo __('Required. Allows you to specify a custom username for the administrator. Default: admin'); ?></p>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="installEmailField"><?php echo __('Administrator email'); ?></label>
						<div class="controls">
							<?php echo Form::input('install[email]', Arr::get($data, 'email', 'admin@yoursite.com'), array(
								'class' => 'input-xlarge', 'id' => 'installEmailField'
							)); ?> <?php echo HTML::label(__('Required.')); ?>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="installURLSuffixField"><?php echo __('URL suffix'); ?></label>
						<div class="controls">
							<?php echo Form::input('install[url_suffix]',  Arr::get($data, 'url_suffix', '.html'), array(
								'class' => 'input-xlarge', 'id' => 'installURLSuffixField'
							)); ?>

							<p class="help-block"><?php echo __('Optional. Add a suffix to simulate static html files.'); ?></p>
						</div>
					</div>
				</fieldset>

				<button class="btn btn-large btn-success pull-right"><?php echo HTML::icon('ok icon-white') . ' ' . __('Install now!'); ?></button>
				<div class="clearfix"></div>

			</form><!--/#installForm-->

			<div class="page-header">
				<h1><?php echo __('Environment Tests'); ?></h1>
			</div>
			<table class="table table-condensed">
				<tr>
					<th>PHP Version</th>
					<?php if (version_compare(PHP_VERSION, '5.3.3', '>=')): ?>
						<td class="pass"><?php echo PHP_VERSION ?></td>
					<?php else: $failed = TRUE ?>
						<td class="fail">Kohana requires PHP 5.3.3 or newer, this version is <?php echo PHP_VERSION ?>.</td>
					<?php endif ?>
				</tr>
				<tr>
					<th>Application Directory</th>
					<?php if (is_dir(APPPATH) AND is_file(APPPATH.'bootstrap'.EXT)): ?>
						<td class="pass"><?php echo APPPATH ?></td>
					<?php else: $failed = TRUE ?>
						<td class="fail">The configured <code>application</code> directory does not exist or does not contain required files.</td>
					<?php endif ?>
				</tr>
				<tr>
					<th>Cache Directory</th>
					<?php if (is_dir(APPPATH) AND is_dir(APPPATH.'cache') AND is_writable(APPPATH.'cache')): ?>
						<td class="pass"><?php echo APPPATH.'cache/' ?></td>
					<?php else: $failed = TRUE ?>
						<td class="fail">The <code><?php echo APPPATH.'cache/' ?></code> directory is not writable.</td>
					<?php endif ?>
				</tr>
				<tr>
					<th>PCRE UTF-8</th>
					<?php if ( ! @preg_match('/^.$/u', 'ñ')): $failed = TRUE ?>
						<td class="fail"><a href="http://php.net/pcre">PCRE</a> has not been compiled with UTF-8 support.</td>
					<?php elseif ( ! @preg_match('/^\pL$/u', 'ñ')): $failed = TRUE ?>
						<td class="fail"><a href="http://php.net/pcre">PCRE</a> has not been compiled with Unicode property support.</td>
					<?php else: ?>
						<td class="pass">Pass</td>
					<?php endif ?>
				</tr>
				<tr>
					<th>SPL Enabled</th>
					<?php if (function_exists('spl_autoload_register')): ?>
						<td class="pass">Pass</td>
					<?php else: $failed = TRUE ?>
						<td class="fail">PHP <a href="http://www.php.net/spl">SPL</a> is either not loaded or not compiled in.</td>
					<?php endif ?>
				</tr>
				<tr>
					<th>Reflection Enabled</th>
					<?php if (class_exists('ReflectionClass')): ?>
						<td class="pass">Pass</td>
					<?php else: $failed = TRUE ?>
						<td class="fail">PHP <a href="http://www.php.net/reflection">reflection</a> is either not loaded or not compiled in.</td>
					<?php endif ?>
				</tr>
				<tr>
					<th>Filters Enabled</th>
					<?php if (function_exists('filter_list')): ?>
						<td class="pass">Pass</td>
					<?php else: $failed = TRUE ?>
						<td class="fail">The <a href="http://www.php.net/filter">filter</a> extension is either not loaded or not compiled in.</td>
					<?php endif ?>
				</tr>
				<tr>
					<th>Iconv Extension Loaded</th>
					<?php if (extension_loaded('iconv')): ?>
						<td class="pass">Pass</td>
					<?php else: $failed = TRUE ?>
						<td class="fail">The <a href="http://php.net/iconv">iconv</a> extension is not loaded.</td>
					<?php endif ?>
				</tr>
				<?php if (extension_loaded('mbstring')): ?>
				<tr>
					<th>Mbstring Not Overloaded</th>
					<?php if (ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING): $failed = TRUE ?>
						<td class="fail">The <a href="http://php.net/mbstring">mbstring</a> extension is overloading PHP's native string functions.</td>
					<?php else: ?>
						<td class="pass">Pass</td>
					<?php endif ?>
				</tr>
				<?php endif ?>
				<tr>
					<th>Character Type (CTYPE) Extension</th>
					<?php if ( ! function_exists('ctype_digit')): $failed = TRUE ?>
						<td class="fail">The <a href="http://php.net/ctype">ctype</a> extension is not enabled.</td>
					<?php else: ?>
						<td class="pass">Pass</td>
					<?php endif ?>
				</tr>
				<tr>
					<th>URI Determination</th>
					<?php if (isset($_SERVER['REQUEST_URI']) OR isset($_SERVER['PHP_SELF']) OR isset($_SERVER['PATH_INFO'])): ?>
						<td class="pass">Pass</td>
					<?php else: $failed = TRUE ?>
						<td class="fail">Neither <code>$_SERVER['REQUEST_URI']</code>, <code>$_SERVER['PHP_SELF']</code>, or <code>$_SERVER['PATH_INFO']</code> is available.</td>
					<?php endif ?>
				</tr>
			</table>
		</div>
	</body>
</html>