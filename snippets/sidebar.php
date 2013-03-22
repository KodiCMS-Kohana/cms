<?php if ( ! URL::match( 'about-us' ) ): ?>
<h3>About Me</h3>

<p>I'm just a demonstration of how easy it is to use KoDi CMS to power a blog. 
	<?php echo HTML::anchor('about-us', 'more ...'); ?></p>

<hr />
<?php endif; ?>

<h4>Favorite Sites</h4>
<ul class="unstyled">
	<li>
		<?php echo HTML::anchor('https://github.com/butschster/kodicms', 'KodiCMS'); ?>
	</li>
</ul>

<?php if ( URL::match( '/' ) ): ?>
	<h3>Recent Entries</h3>
	<?php $page_article = $page->find( '/articles/' ); ?>
	<?php if($page_article): ?>
	<ul>
		<?php foreach ( $page_article->children( array( 'limit' => 10, 'order' => 'page.created_on DESC' ) ) as $article ): ?>
			<li><?php echo $article->link(); ?></li> 
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
<?php endif; ?>