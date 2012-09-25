<!DOCTYPE html>
<html lang="<?php echo I18n::lang(); ?>">
	<head>
		<title><?php echo $page->title(); ?> | <?php echo Setting::get('admin_title'); ?></title>

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="robots" content="index, follow" />
		<meta name="description" content="<?php echo ($page->description() != '') ? $page->description() : 'Default description goes here'; ?>" />
		<meta name="keywords" content="<?php echo ($page->keywords() != '') ? $page->keywords() : 'default, keywords, here'; ?>" />
		<meta name="author" content="Author Name" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php echo HTML::style( PUBLIC_URL . 'themes/normal/screen.css' ) . "\n"; ?>
        <?php echo HTML::style( PUBLIC_URL . 'themes/bootstrap.css' ) . "\n"; ?>
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
		<link rel="alternate" type="application/rss+xml" title="Default RSS Feed" href="<?php echo URL::site('rss.xml', TRUE); ?>" />
	</head>
	<body>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" href="/"><?php echo Setting::get('admin_title'); ?></a>
                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <li class="<?if(Request::current()->uri() == '/'):?>active<?endif;?>"><a href="/">Home</a></li>
                        <?php foreach($page->find('/')->children() as $item): ?>
                        <li class="<?if(Request::current()->uri() == $item->url):?>active<?endif;?>"><a href="<?=$item->url?>"><?=$item->title?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <form class="navbar-form pull-right">
                        <input class="span2" type="text" placeholder="Email">
                        <input class="span2" type="password" placeholder="Password">
                        <button type="submit" class="btn">Sign in</button>
                    </form>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>

		<div id="page">
			<?php echo $page->includeSnippet( 'header' ); ?>
			<div id="content">

				<h2><?php echo $page->title(); ?></h2>
				<?php echo $page->content(); ?> 
				<?php echo $page->content( 'extended' ); ?> 

			</div> <!-- end #content -->
			<div id="sidebar">

				<?php echo $page->content( 'sidebar', TRUE ); ?> 

			</div> <!-- end #sidebar -->
			<?php echo $page->includeSnippet( 'footer' ); ?>
		</div> <!-- end #page -->
        <?php echo HTML::script( PUBLIC_URL . 'js/jquery-1.8.2.min.js' ) . "\n"; ?>
        <?php echo HTML::script( PUBLIC_URL . 'js/bootstrap.min.js' ) . "\n"; ?>
	</body>
</html>