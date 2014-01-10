<?php foreach ($pages as $article): ?>
<item>
    <title><?php echo $article->title(); ?></title>
    <description><![CDATA[<?php if (Part::exists($article, 'summary')) { echo Part::content($article, 'summary'); } else { echo strip_tags(Part::content($article)); } ?>]]></description>
    <pubDate><?php echo date('r', strtotime($article->published_on)); ?></pubDate>
    <link><?php echo $article->url(); ?></link>
<guid><?php echo $article->url(); ?></guid>
</item>
<?php endforeach; ?>