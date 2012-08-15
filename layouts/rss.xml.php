<?php echo '<?'; ?>xml version="1.0" encoding="UTF-8"<?php echo '?>'; ?> 
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<title>Flexo CMS</title>
	<link><?php echo BASE_URL; ?></link>
	<atom:link href="<?php echo get_url('rss.xml'); ?>" rel="self" type="application/rss+xml" />
	<language>en-us</language>
	<copyright>Copyright <?php echo date('Y'); ?>, myflexo.ru</copyright>
	<pubDate><?php echo strftime('%a, %d %b %Y %H:%M:%S'); ?></pubDate>
	<lastBuildDate><?php echo strftime('%a, %d %b %Y %H:%M:%S'); ?></lastBuildDate>
	<category>any</category>
	<generator>Flexo CMS</generator>
	<description>The main news feed from Frog CMS.</description>
	<docs>http://www.rssboard.org/rss-specification</docs>
	<?php $articles = $this->find('articles'); ?>
	<?php foreach ($articles->children(array('limit' => 10, 'order' => 'page.created_on DESC')) as $article): ?>
	<item>
		<title><?php echo $article->title(); ?></title>
		<description><?php if ($article->hasContent('short')) { echo htmlentities(strip_tags($article->content('short')), ENT_COMPAT, 'UTF-8'); } else { echo htmlentities(strip_tags($article->content()), ENT_COMPAT, 'UTF-8');; } ?></description>
		<link><?php echo $article->url(); ?></link>
		<pubDate><?php echo $article->date('%a, %d %b %Y %H:%M:%S'); ?></pubDate>
		<guid><?php echo $article->url(); ?></guid>
	</item>
	<?php endforeach; ?>
</channel>
</rss>