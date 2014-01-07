<?php foreach ($pages as $article): ?>
<item>
    <title><?php echo $article->title(); ?></title>
    <description><![CDATA[<?php if ($article->has_content('summary')) { echo $article->content('summary'); } else { echo strip_tags($article->content()); } ?>]]></description>
    <pubDate><?php echo date('r', strtotime($article->published_on)); ?></pubDate>
    <link><?php echo $article->url(); ?></link>
<guid><?php echo $article->url(); ?></guid>
</item>
<?php endforeach; ?>