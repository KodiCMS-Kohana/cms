<?php defined('SYSPATH') OR die('No direct script access.') ?>
<?php
	// Unique error identifier
	$error_id = uniqid('error');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="generator" content="<?php echo CMS_NAME . ' v.' . CMS_VERSION; ?>">
		<meta name="author" content="ButscH" />
		<title>System error &ndash; <?php echo Config::get('site', 'title' ); ?></title>
		<link href="<?php echo ADMIN_RESOURCES; ?>favicon.ico" rel="favourites icon" />

		<script type="text/javascript">
			var MESSAGE_ERRORS = [];
			var MESSAGE_SUCCESS = [];
		</script>
		<?php echo Assets::css(); ?>
		<?php echo Assets::js(); ?>
		
		<style type="text/css">
			.error-container .error-text {
				margin-top: 20px;
			}
			
			.sources pre {
				margin: 0;
				background: #fff;
			}
			.js .collapsed { display: none; }
			
			.panel-title > a {
				color: #428bca;
			}
			
			pre.source span.line {
				display: block;
			}
			pre.source span.highlight {
				background: #e66454;
				color: #fff;
				font-weight: bold;
			}
			
		</style>

		<script type="text/javascript">
			document.documentElement.className = document.documentElement.className + ' js';
			function koggle(elem)
			{
				elem = document.getElementById(elem);

				if (elem.style && elem.style['display'])
					// Only works with the "style" attr
					var disp = elem.style['display'];
				else if (elem.currentStyle)
					// For MSIE, naturally
					var disp = elem.currentStyle['display'];
				else if (window.getComputedStyle)
					// For most other browsers
					var disp = document.defaultView.getComputedStyle(elem, null).getPropertyValue('display');

				// Toggle the state of the "display" style
				elem.style.display = disp == 'block' ? 'none' : 'block';
				return false;
			}
		</script>
	</head>
	<body id="body_frontend" class="theme-default">
		<div class="frontend-header">
			<a href="/" class="logo">
				<?php echo HTML::image(ADMIN_RESOURCES . 'images/logo-color.png'); ?>
			</a>
		</div>
		
		<div class="error-container">
			<div class="error-code"><?php echo $code; ?></div>
			
			<div class="error-text">
				<span class="type text-light-gray">
					<?php echo $class ?>:</span> <span class="message"><?php echo htmlspecialchars( (string) $message, ENT_QUOTES, Kohana::$charset, TRUE); ?>
				</span>
			</div>
		</div>
		<br />
		<br />
		<div class="container-fluid">
			<div id="<?php echo $error_id ?>">
				<div class="panel sources">
					<div class="panel-heading">
						<span class="file panel-title text-bold"><?php echo Debug::path($file) ?> <span class="badge badge-danger"><?php echo $line; ?></span>
					</div>

					<div class="language-php" data-line="6" data-start="<?php echo $line; ?>"><?php echo Debug::source($file, $line) ?></div>

					<?php foreach (Debug::trace($trace) as $i => $step): ?>
						<div class="panel-heading">
							<span class="file panel-title">
								<?php if ($step['file']): $source_id = $error_id.'source'.$i; ?>
									<a href="#<?php echo $source_id ?>" onclick="return koggle('<?php echo $source_id ?>')"><?php echo Debug::path($step['file']) ?> <span class="badge badge-danger"><?php echo $step['line']; ?></span></a>
								<?php else: ?>
									{<?php echo __('PHP internal call') ?>}
								<?php endif ?>
							</span>
							&raquo;
							<?php echo $step['function'] ?>(<?php if ($step['args']): $args_id = $error_id.'args'.$i; ?><a href="#<?php echo $args_id ?>" onclick="return koggle('<?php echo $args_id ?>')"><?php echo __('arguments') ?></a><?php endif ?>)
						</div>
							<?php if (isset($args_id)): ?>
							<div id="<?php echo $args_id ?>" class="collapsed">
								<table class="table table-striped">
								<?php foreach ($step['args'] as $name => $arg): ?>
									<tr>
										<td><code><?php echo $name ?></code></td>
										<td><pre class="language-php"><?php echo Debug::dump($arg) ?></pre></td>
									</tr>
								<?php endforeach ?>
								</table>
							</div>
							<?php endif ?>
							<?php if (isset($source_id)): ?>
								<div id="<?php echo $source_id ?>" class="source collapsed language-php"><?php echo $step['source'] ?></div>
							<?php endif ?>
						<?php unset($args_id, $source_id); ?>
					<?php endforeach ?>
				</div>
			</div>
			
			<div class="panel">
				<div class="panel-heading">
					<span class="panel-title">
						<a href="#<?php echo $env_id = $error_id.'environment' ?>" onclick="return koggle('<?php echo $env_id ?>')"><?php echo __('Environment') ?></a>
					</span>
				</div>
			
				<div id="<?php echo $env_id ?>" class="panel-body collapsed">
					<?php $included = get_included_files() ?>
					<h4><a href="#<?php echo $env_id = $error_id.'environment_included' ?>" onclick="return koggle('<?php echo $env_id ?>')"><?php echo __('Included files') ?></a> <span class="label label-success"><?php echo count($included) ?></span></h4>
					<div id="<?php echo $env_id ?>" class="collapsed">
						<table class="table table-striped">
							<?php foreach ($included as $file): ?>
							<tr>
								<td><code><?php echo Debug::path($file) ?></code></td>
							</tr>
							<?php endforeach ?>
						</table>
					</div>
					<?php $included = get_loaded_extensions() ?>
					<h4><a href="#<?php echo $env_id = $error_id.'environment_loaded' ?>" onclick="return koggle('<?php echo $env_id ?>')"><?php echo __('Loaded extensions') ?></a> <span class="label label-success"><?php echo count($included) ?></span></h4>
					<div id="<?php echo $env_id ?>" class="collapsed">
						<table class="table table-striped">
							<?php foreach ($included as $file): ?>
							<tr>
								<td><code class="language-php"><?php echo Debug::path($file) ?></code></td>
							</tr>
							<?php endforeach ?>
						</table>
					</div>
					<?php foreach (array('_SESSION', '_GET', '_POST', '_FILES', '_COOKIE', '_SERVER') as $var): ?>
					<?php if (empty($GLOBALS[$var]) OR ! is_array($GLOBALS[$var])) continue ?>
					<h4><a href="#<?php echo $env_id = $error_id.'environment'.strtolower($var) ?>" onclick="return koggle('<?php echo $env_id ?>')">$<?php echo $var ?></a></h4>
					<div id="<?php echo $env_id ?>" class="collapsed">
						<table class="table table-striped">
							<?php foreach ($GLOBALS[$var] as $key => $value): ?>
							<tr>
								<td><code><?php echo htmlspecialchars((string) $key, ENT_QUOTES, Kohana::$charset, TRUE); ?></code></td>
								<td><pre class="language-php"><?php echo Debug::dump($value) ?></pre></td>
							</tr>
							<?php endforeach ?>
						</table>
					</div>
					<?php endforeach ?>
				</div>
			</div>
		</div>
	</body>
</html>
