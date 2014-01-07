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