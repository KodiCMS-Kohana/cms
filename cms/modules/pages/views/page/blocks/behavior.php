<div class="panel-body no-padding">
	<?php foreach ($behavior->router()->routes() as $route => $params): ?>
	<?php if(empty($route)) continue; ?>
	<div class="panel no-margin-b">
		<div class="panel-heading">
			<code class="panel-title"><?php echo $page->get_uri() . HTML::chars($route); ?></code>
		</div>
		<div class="panel-body">
			<?php if(isset($params['regex']) AND is_array($params['regex'])): ?>
			<dl class="dl-horizontal no-margin">
				<?php foreach ($params['regex'] as $key => $regex): ?>
				<dt><?php echo $key; ?></dt>
				<dd><?php echo $regex; ?></dd>
				<?php endforeach; ?>
			</dl>
			<?php endif; ?>
		</div>
	</div>
	<?php endforeach; ?>
</div>