SET FOREIGN_KEY_CHECKS=0;

INSERT INTO `TABLE_PREFIX_pages` (`id`, `title`, `slug`, `breadcrumb`, `keywords`, `description`, `parent_id`, `layout_file`, `behavior_id`, `status_id`, `created_on`, `published_on`, `updated_on`, `created_by_id`, `updated_by_id`, `position`, `needs_login`) VALUES
(1, 'Home', '', 'Home', '', '', 0, 'normal', '', 100, '__DATE__', '__DATE__', '__DATE__', 1, 1, 0, 0),
(2, 'Page not found', 'page-not-found', 'Page not found', '', '', 1, '', 'page_not_found', 101, '__DATE__', '__DATE__', '__DATE__', 1, 1, 0, 2),
(3, 'About us', 'about-us', 'About us', '', '', 1, '', '', 100, '__DATE__', '__DATE__', '__DATE__', NULL, 1, 3, 2),
(4, 'RSS Feed', 'rss.xml', 'RSS Feed', '', '', 1, 'none', '', 101, '__DATE__', '__DATE__', '__DATE__', 1, 1, 1, 2),
(5, 'My first article', 'my-first-article', 'My first article', '', '', 8, '', '', 100, '__DATE__', '__DATE__', NULL, NULL, NULL, NULL, 2),
(6, '%B %Y archive', 'b-y-archive', '%B %Y archive', '', '', 8, '', 'archive_month_index', 101, '__DATE__', '__DATE__', '__DATE__', 1, 1, 1, 2),
(7, 'My second article', 'my-second-article', 'My second article', '', '', 8, '', '', 100, '__DATE__', '__DATE__', NULL, NULL, NULL, NULL, 2),
(8, 'Articles', 'articles', 'Articles', '', '', 1, '', 'archive', 100, '__DATE__', '__DATE__', '__DATE__', NULL, 1, 2, 2);

INSERT INTO `TABLE_PREFIX_page_parts` (`id`, `name`, `filter_id`, `content`, `content_html`, `page_id`, `is_protected`) VALUES
(1, 'body', 'codemirror', '<?php $page_article = $page->find(''/articles/''); ?>\n<?php $last_article = $page_article->children(array(''limit'' => 1, ''order'' => ''page.created_on DESC'')); ?>\n\n<div class="media">\n    <a class="pull-left" href="#">\n    	<img class="media-object" data-src="holder.js/220x100">\n  	</a>\n    <div class="media-body">\n        <h3 class="media-heading"><?php echo $last_article->link(); ?></h3>\n        <?php echo $last_article->content(); ?>\n        <?php if ($last_article->has_content(''extended'')) echo $last_article->link(''Continue Reading…''); ?>\n        <p class="info">Posted by <?php echo $last_article->author(); ?> on <?php echo $last_article->date(); ?></p>\n    </div>\n</div>\n<br />\n<div class="well">\n    <?php foreach ($page_article->children(array(''limit'' => 4, ''offset'' => 1, ''order'' => ''page.created_on DESC'')) as $article): ?>\n    <div class="media">\n        <div class="media-body">\n            <h4 class="media-heading"><?php echo $article->link(); ?></h4>\n            <?php echo $article->content(); ?>\n            <?php if ($article->has_content(''extended'')) echo $article->link(''Continue Reading…''); ?>\n            <p class="info">Posted by <?php echo $article->author(); ?> on <?php echo $article->date(); ?></p>\n        </div>\n    </div>\n    <?php endforeach; ?>\n</div>', '<?php $page_article = $page->find(''/articles/''); ?>\n<?php $last_article = $page_article->children(array(''limit'' => 1, ''order'' => ''page.created_on DESC'')); ?>\n\n<div class="media">\n    <a class="pull-left" href="#">\n    	<img class="media-object" data-src="holder.js/220x100">\n  	</a>\n    <div class="media-body">\n        <h3 class="media-heading"><?php echo $last_article->link(); ?></h3>\n        <?php echo $last_article->content(); ?>\n        <?php if ($last_article->has_content(''extended'')) echo $last_article->link(''Continue Reading…''); ?>\n        <p class="info">Posted by <?php echo $last_article->author(); ?> on <?php echo $last_article->date(); ?></p>\n    </div>\n</div>\n<br />\n<div class="well">\n    <?php foreach ($page_article->children(array(''limit'' => 4, ''offset'' => 1, ''order'' => ''page.created_on DESC'')) as $article): ?>\n    <div class="media">\n        <div class="media-body">\n            <h4 class="media-heading"><?php echo $article->link(); ?></h4>\n            <?php echo $article->content(); ?>\n            <?php if ($article->has_content(''extended'')) echo $article->link(''Continue Reading…''); ?>\n            <p class="info">Posted by <?php echo $article->author(); ?> on <?php echo $article->date(); ?></p>\n        </div>\n    </div>\n    <?php endforeach; ?>\n</div>', 1, 0),
(2, 'body', 'redactor', '<h3>Sorry</h3>\n\n<p>This page not found<br>\n</p>\n', '<h3>Sorry</h3>\n\n<p>This page not found<br>\n</p>\n', 2, 0),
(3, 'body', '', '<p>This is my site. I live in this city ... I do some nice things, like this and that ... <br></p>\n', '<p>This is my site. I live in this city ... I do some nice things, like this and that ... <br></p>\n', 3, 0),
(5, 'body', 'codemirror', '<?php $article = $page->find(''/articles/''); ?>\n<?php $archives = $article->archive->archives_by_month(); ?>\n\n<h3>Archives By Month</h3>\n<div class="media">\n	<?php foreach ($archives as $date): ?>\n    <div class="media-body">\n        <h4 class="media-heading"><a href="<?php echo BASE_URL . $page->url .''/''. $date . URL_SUFFIX; ?>"><?php echo strftime(''%B %Y'', strtotime(strtr($date, ''/'', ''-''))); ?></a></h4>\n    </div>\n	<?php endforeach; ?>\n</div>', '<?php $article = $page->find(''/articles/''); ?>\n<?php $archives = $article->archive->archives_by_month(); ?>\n\n<h3>Archives By Month</h3>\n<div class="media">\n	<?php foreach ($archives as $date): ?>\n    <div class="media-body">\n        <h4 class="media-heading"><a href="<?php echo BASE_URL . $page->url .''/''. $date . URL_SUFFIX; ?>"><?php echo strftime(''%B %Y'', strtotime(strtr($date, ''/'', ''-''))); ?></a></h4>\n    </div>\n	<?php endforeach; ?>\n</div>', 8, 0),
(6, 'body', 'redactor', '<p>&nbsp;My first test of my first article.</p>', '<p>&nbsp;My first test of my first article.</p>', 5, 0),
(7, 'body', 'redactor', '<p>&nbsp;This is my second article.</p>', '<p>&nbsp;This is my second article.</p>', 7, 0),
(8, 'body', 'codemirror', '<?php $archives = $page->archive->get(); ?>\n\n<?php foreach ($archives as $archive): ?>\n<div class="media">\n    <a class="pull-left" href="<?php echo $archive->url(); ?>">\n        <img class="media-object" data-src="holder.js/64x64">\n    </a>\n    <div class="media-body">\n        <h4 class="media-heading"><?php echo $archive->link(); ?></h4>\n        <p class="info">Posted by <?php echo $archive->author(); ?> on <?php echo $archive->date(); ?> \n        </p>\n    </div>\n</div>\n<hr />\n<?php endforeach; ?>', '<?php $archives = $page->archive->get(); ?>\n\n<?php foreach ($archives as $archive): ?>\n<div class="media">\n    <a class="pull-left" href="<?php echo $archive->url(); ?>">\n        <img class="media-object" data-src="holder.js/64x64">\n    </a>\n    <div class="media-body">\n        <h4 class="media-heading"><?php echo $archive->link(); ?></h4>\n        <p class="info">Posted by <?php echo $archive->author(); ?> on <?php echo $archive->date(); ?> \n        </p>\n    </div>\n</div>\n<hr />\n<?php endforeach; ?>', 6, 0),
(9, 'body', 'codemirror', '<?php echo ''<?''; ?>xml version="1.0" encoding="UTF-8"<?php echo ''?>''; ?> \n<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">\n	<channel>\n		<title>KoDi CMS</title>\n		<link><?php echo BASE_URL ?></link>\n		<atom:link href="<?php echo BASE_URL ?>/rss.xml" rel="self" type="application/rss+xml" />\n		<language>en-us</language>\n		<copyright>Copyright <?php echo date(''Y''); ?>, madebyfrog.com</copyright>\n		<pubDate><?php echo strftime(''%a, %d %b %Y %H:%M:%S %z''); ?></pubDate>\n		<lastBuildDate><?php echo strftime(''%a, %d %b %Y %H:%M:%S %z''); ?></lastBuildDate>\n		<category>any</category>\n		<generator>KoDi CMS</generator>\n		<description>The main news feed from KoDi CMS.</description>\n		<docs>http://www.rssboard.org/rss-specification</docs>\n		<?php $articles = $page->find(''/articles/''); ?>\n		<?php foreach ($articles->children(array(''limit'' => 10, ''order'' => ''page.created_on DESC'')) as $article): ?>\n		<item>\n			<title><?php echo $article->title(); ?></title>\n			<description><![CDATA[<?php if ($article->has_content(''summary'')) { echo $article->content(''summary''); } else { echo strip_tags($article->content()); } ?>]]></description>\n			<pubDate><?php echo $article->date(''%a, %d %b %Y %H:%M:%S %z''); ?></pubDate>\n			<link><?php echo $article->url(); ?></link>\n			<guid><?php echo $article->url(); ?></guid>\n		</item>\n		<?php endforeach; ?>\n	</channel>\n</rss>', '<?php echo ''<?''; ?>xml version="1.0" encoding="UTF-8"<?php echo ''?>''; ?> \n<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">\n	<channel>\n		<title>KoDi CMS</title>\n		<link><?php echo BASE_URL ?></link>\n		<atom:link href="<?php echo BASE_URL ?>/rss.xml" rel="self" type="application/rss+xml" />\n		<language>en-us</language>\n		<copyright>Copyright <?php echo date(''Y''); ?>, madebyfrog.com</copyright>\n		<pubDate><?php echo strftime(''%a, %d %b %Y %H:%M:%S %z''); ?></pubDate>\n		<lastBuildDate><?php echo strftime(''%a, %d %b %Y %H:%M:%S %z''); ?></lastBuildDate>\n		<category>any</category>\n		<generator>KoDi CMS</generator>\n		<description>The main news feed from KoDi CMS.</description>\n		<docs>http://www.rssboard.org/rss-specification</docs>\n		<?php $articles = $page->find(''/articles/''); ?>\n		<?php foreach ($articles->children(array(''limit'' => 10, ''order'' => ''page.created_on DESC'')) as $article): ?>\n		<item>\n			<title><?php echo $article->title(); ?></title>\n			<description><![CDATA[<?php if ($article->has_content(''summary'')) { echo $article->content(''summary''); } else { echo strip_tags($article->content()); } ?>]]></description>\n			<pubDate><?php echo $article->date(''%a, %d %b %Y %H:%M:%S %z''); ?></pubDate>\n			<link><?php echo $article->url(); ?></link>\n			<guid><?php echo $article->url(); ?></guid>\n		</item>\n		<?php endforeach; ?>\n	</channel>\n</rss>', 4, 0);

INSERT INTO `TABLE_PREFIX_page_roles` (`page_id`, `role_id`) VALUES
(1, 2),
(2, 2),
(3, 2),
(3, 3),
(3, 4),
(4, 2),
(4, 3),
(4, 4),
(5, 2),
(5, 3),
(5, 4),
(6, 2),
(6, 3),
(6, 4),
(7, 2),
(7, 3),
(7, 4),
(8, 2),
(8, 3),
(8, 4);

INSERT INTO `TABLE_PREFIX_roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'administrator', 'Administrative user, has access to everything.'),
(3, 'developer', 'Developers role'),
(4, 'editor', '');

INSERT INTO `TABLE_PREFIX_roles_users` (`user_id`, `role_id`) VALUES
(1, 1),
(1, 2);

INSERT INTO `TABLE_PREFIX_settings` (`name`, `value`) VALUES
('admin_title', '__SITE_NAME__'),
('default_locale', '__LANG__'),
('default_status_id', '100'),
('default_filter_id', 'redactor'),
('default_tab', 'page'),
('allow_html_title', 'off'),
('profiling', 'no'),
('date_format', 'd F Y'),
('debug', 'no'),
('find_similar', 'yes'),
('plugins', 'a:8:{s:7:"archive";b:1;s:10:"codemirror";b:1;s:11:"filemanager";b:1;s:14:"page_not_found";b:1;s:8:"redactor";b:1;s:13:"slug_translit";b:1;s:9:"tagsinput";b:1;s:11:"maintenance";b:1;}');

INSERT INTO `TABLE_PREFIX_users` (`id`, `email`, `username`, `password`, `logins`, `last_login`) VALUES
(1, '__EMAIL__', '__USERNAME__', '__ADMIN_PASSWORD__', 0, 0);

INSERT INTO `TABLE_PREFIX_user_profiles` (`id`, `name`, `user_id`, `created_on`) VALUES
(1, 'Administrator', 1, '__DATE__');

INSERT INTO `TABLE_PREFIX_layout_blocks` (`layout_name`, `block`, `position`) VALUES
('none', 'body', 0),
('normal', 'body', 3),
('normal', 'bradcrumbs', 1),
('normal', 'extended', 4),
('normal', 'footer', 6),
('normal', 'header', 0),
('normal', 'sidebar', 5),
('normal', 'top_banner', 2);

INSERT INTO `TABLE_PREFIX_page_widgets` (`page_id`, `widget_id`, `block`) VALUES
(1, 2, 'bradcrumbs'),
(1, 3, 'footer'),
(1, 1, 'header'),
(1, 4, 'sidebar'),
(1, 5, 'top_banner'),
(2, 2, 'bradcrumbs'),
(2, 3, 'footer'),
(2, 1, 'header'),
(2, 4, 'sidebar'),
(3, 2, 'bradcrumbs'),
(3, 3, 'footer'),
(3, 1, 'header'),
(3, 4, 'sidebar'),
(5, 2, 'bradcrumbs'),
(5, 3, 'footer'),
(5, 1, 'header'),
(5, 4, 'sidebar'),
(6, 2, 'bradcrumbs'),
(6, 3, 'footer'),
(6, 1, 'header'),
(6, 4, 'sidebar'),
(7, 2, 'bradcrumbs'),
(7, 3, 'footer'),
(7, 1, 'header'),
(7, 4, 'sidebar'),
(8, 2, 'bradcrumbs'),
(8, 3, 'footer'),
(8, 1, 'header'),
(8, 4, 'sidebar');

INSERT INTO `TABLE_PREFIX_widgets` (`id`, `type`, `template`, `name`, `description`, `created_on`, `code`) VALUES
(1, 'page_menu', 'header', 'Header menu', '', '2013-03-22 18:38:11', 'O:22:"Model_Widget_Page_Menu":13:{s:7:"exclude";a:3:{i:0;s:1:"6";i:1;s:1:"4";i:2;s:1:"2";}s:2:"id";s:2:"10";s:4:"type";s:9:"page_menu";s:4:"name";s:11:"Header menu";s:11:"description";s:0:"";s:6:"header";s:0:"";s:8:"template";s:6:"header";s:15:"template_params";a:0:{}s:5:"block";N;s:7:"caching";b:0;s:14:"cache_lifetime";i:0;s:9:"throw_404";b:0;s:8:"\0*\0_data";a:3:{s:15:"match_all_paths";i:0;s:7:"page_id";s:1:"1";s:8:"continue";s:0:"";}}'),
(2, 'page_breadcrumbs', 'bradcrumbs', 'Хлебные крошки', '', '2013-03-22 19:45:02', 'O:29:"Model_Widget_Page_Breadcrumbs":13:{s:7:"exclude";a:1:{i:0;s:1:"6";}s:2:"id";s:2:"11";s:4:"type";s:16:"page_breadcrumbs";s:4:"name";s:27:"Хлебные крошки";s:11:"description";s:0:"";s:6:"header";s:0:"";s:8:"template";s:10:"bradcrumbs";s:15:"template_params";a:0:{}s:5:"block";N;s:7:"caching";b:0;s:14:"cache_lifetime";i:0;s:9:"throw_404";b:0;s:8:"\0*\0_data";a:1:{s:8:"continue";s:0:"";}}'),
(3, 'html', 'footer', 'Footer', '', '2013-03-22 20:29:27', 'O:17:"Model_Widget_HTML":12:{s:2:"id";s:2:"12";s:4:"type";s:4:"html";s:4:"name";s:6:"Footer";s:11:"description";s:0:"";s:6:"header";s:0:"";s:8:"template";s:6:"footer";s:15:"template_params";a:0:{}s:5:"block";N;s:7:"caching";b:0;s:14:"cache_lifetime";i:0;s:9:"throw_404";b:0;s:8:"\0*\0_data";a:1:{s:8:"continue";s:0:"";}}'),
(4, 'html', 'sidebar', 'Sidebar', '', '2013-03-22 20:41:25', 'O:17:"Model_Widget_HTML":12:{s:2:"id";s:2:"13";s:4:"type";s:4:"html";s:4:"name";s:7:"Sidebar";s:11:"description";s:0:"";s:6:"header";s:0:"";s:8:"template";s:7:"sidebar";s:15:"template_params";a:0:{}s:5:"block";N;s:7:"caching";b:0;s:14:"cache_lifetime";i:0;s:9:"throw_404";b:0;s:8:"\0*\0_data";a:1:{s:8:"continue";s:0:"";}}'),
(5, 'html', 'top_banner', 'Top banner', '', '2013-03-22 20:50:41', 'O:17:"Model_Widget_HTML":12:{s:2:"id";s:2:"14";s:4:"type";s:4:"html";s:4:"name";s:10:"Top banner";s:11:"description";s:0:"";s:6:"header";s:0:"";s:8:"template";s:10:"top_banner";s:15:"template_params";a:0:{}s:5:"block";N;s:7:"caching";b:0;s:14:"cache_lifetime";i:0;s:9:"throw_404";b:0;s:8:"\0*\0_data";a:1:{s:8:"continue";s:0:"";}}');

SET FOREIGN_KEY_CHECKS=1;