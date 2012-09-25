<div id="header">
	<h1><?php echo HTML::anchor(Url::base(TRUE), Setting::get('admin_title')); ?> <span>content management simplified</span></h1>
	
	<div id="nav">
		<ul>
			<li>
				<?php echo HTML::anchor(Url::base(TRUE), 'Home', array(
					'class' => URL::match('/') ? 'current' : ''
				)); ?>
			</li>
			<?php foreach($page->find('/')->children() as $menu): ?>
			<li><?php echo $menu->link(NULL, NULL, TRUE); ?></li>
			<?php endforeach; ?> 
		</ul>
	</div>
</div>