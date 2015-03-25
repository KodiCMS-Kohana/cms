<?php if (is_array($array)): ?>
<ul class="page-toc list-unstyled">
	<?php foreach ($array as $item): ?>
	<li>
		<?php if ($item['level'] > 1): ?>
		<?php echo str_repeat('&nbsp;', ($item['level'] - 1) * 4) ?>
		<?php endif ?>
		<?php echo HTML::anchor(Request::current()->uri().'#'.$item['id'], $item['name'], NULL, NULL, TRUE); ?>
	</li>
	<?php endforeach; ?>
</ul>
<hr />
<?php endif ?>
