<?php

$articles = $this->find('articles');

?>

<div class="articles-list">
	<?php foreach ($articles->children(array('limit' => 10, 'offset' => (isset($_GET['offset']) ? (int)$_GET['offset']: 0))) as $item): ?>
	<article class="item">
		<h2 class="title"><?php echo $item->link(); ?></h2>
		<time class="date"><small><?php echo $item->date(); ?></small></time>
		<div class="content">
			<?php echo $item->content('short'); ?>
		</div>
		<?php if ($tags = $item->tags() && !empty($tags)): ?>
		<div class="tags"><small>Tags: <?php echo join($tags, ', '); ?></small></div>
		<?php endif; ?>
	</article>
	<?php endforeach; ?>
</div><!--/.articles-list-->

<?php

$articles_count = $articles->childrenCount();
$offset = (isset($_GET['offset']) ? (int)$_GET['offset']: 0);

?>

<nav class="pager">
	<?php if($articles_count > 10 && ($offset+10) < $articles_count): ?>
	<a class="pager-old" href="<?php echo get_url('articles'); ?>?offset=<?php echo ($offset+10); ?>">Older posts</a>
	<?php endif; ?>

	<a class="pager-home" href="<?php echo get_url('articles'); ?>">Home</a>

	<?php if($offset >= 10): ?>
	<a class="pager-new" href="<?php echo get_url('articles'); ?>?offset=<?php echo ($offset-10); ?>">Newer posts</a>
	<?php endif; ?>
</nav>