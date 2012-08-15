<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title><?php echo $this->title(); ?> | <?php echo Setting::get('admin_title'); ?></title>
		
		<link href="<?php echo PUBLIC_URL; ?>themes/demo/favicon.ico" rel="favourites icon" />
		
		<link href="<?php echo PUBLIC_URL; ?>themes/demo/stylesheets/layout.css" media="screen" rel="stylesheet" type="text/css" charset="utf-8" />
		
		<!--[if IE]>
		<style type="text/css">
			#layout {
				border-color:#e0e1e3;
				border-style:solid;
				border-width:0 1px 1px 1px;
			}
		</style>
		<script type="text/javascript">
			// IE HTML5 hack
			if (document.all)
			{
				var e = ['abbr', 'article', 'aside', 'audio', 'canvas', 'datalist', 'details', 'figure', 'footer', 'header', 'hgroup', 'mark', 'menu', 'meter', 'nav', 'output', 'progress', 'section', 'time', 'video'];
				for(i in e) document.createElement(e[i]);
			}
		</script>
		<![endif]-->
		
	</head>
	<body>
		
		<div id="layout">
			<header id="header">
				<div class="logo"><a href="<?php echo get_url(); ?>"><em><?php echo Setting::get('admin_title'); ?></em></a> <small>Light flexible content management system</small></div>
			</header>
			
			<nav id="nav">
				<a href="<?php echo get_url(); ?>" <?php if(!$this->slug) echo('class="current"'); ?> >Home</a>
				
				<?php foreach($this->find('/')->children() as $item): ?>
				<?php echo $item->link(null, null, true); ?>
				<?php endforeach; ?>
			</nav>
			
			<div id="middler">
				<aside id="sidebar">
					
					<?php echo $this->content('sidebar', true); ?>
					
				</aside><!--/#sidebar-->
				<section id="content">
					
					<?php if($this->slug) echo($this->breadcrumbs(' &raquo; ')); ?>
					
					<h1><?php echo $this->title(); ?></h1>
					
					<?php echo $this->content(); ?>
					
				</section><!--/#content-->
			</div><!--/#middler-->
			
			<footer id="footer">
				<p class="copyrights">© Flexo CMS demonstration site, 2010‒2011</p>
				<p class="madeby">Made by <a href="http://flexo.up.dn.ua/">Flexo CMS</a></p>
			</footer>
		</div><!--/#layout-->
		
	</body>
</html>