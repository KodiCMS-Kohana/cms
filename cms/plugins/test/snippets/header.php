<br />
<div class="navbar">
	<div class="navbar-inner">
		<a class="brand" href="/"><?php echo Config::get('site', 'title'); ?></a>
		<ul class="nav">
			<?php if ( ! URL::match( '/' ) ): ?>
			<li>
				<?php echo HTML::anchor('', 'Home'); ?>
			</li>
			<?php endif; ?>
			<?php foreach($pages as $page): ?>
			<li class="<?php echo $page['is_active'] ? 'active' : ''; ?>">
				<?php echo HTML::anchor($page['uri'], $page['title']); ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>