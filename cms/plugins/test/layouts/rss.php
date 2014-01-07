<?php echo '<?'; ?>xml version="1.0" encoding="UTF-8"<?php echo '?>'; ?> 
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title><?php echo Config::get('site', 'title'); ?></title>
		<link><?php echo BASE_URL ?></link>
		<atom:link href="<?php echo BASE_URL ?>/rss.xml" rel="self" type="application/rss+xml" />
		<language>en-us</language>
		<copyright>Copyright <?php echo date('Y'); ?>, kodicms.ru</copyright>
		<pubDate><?php echo date('r'); ?></pubDate>
		<lastBuildDate><?php echo date('r'); ?></lastBuildDate>
		<category>any</category>
		<generator>KodiCMS</generator>
		<description>The main news feed from KodiCMS.</description>
		<docs>http://www.rssboard.org/rss-specification</docs>
		<?php Block::run('body'); ?>
	</channel>
</rss>