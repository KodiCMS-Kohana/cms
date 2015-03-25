<ul class="pagination">
	<?php if ($first_page !== FALSE): ?>
	<li><a href="<?php echo HTML::chars($page->url($first_page)) ?>" rel="first"><?php echo __('First') ?></a></li>
	<?php else: ?>
		<li class="active"><?php echo __('First') ?></li>
	<?php endif ?>

	<?php if ($previous_page !== FALSE): ?>
		<li><a href="<?php echo HTML::chars($page->url($previous_page)) ?>" rel="prev"><?php echo __('Previous') ?></a></li>
	<?php else: ?>
		<li class="active"><?php echo __('Previous') ?></li>
	<?php endif ?>

	<?php for ($i = 1; $i <= $total_pages; $i++): ?>
		<?php if ($i == $current_page): ?>
			<li class="active"><?php echo $i ?></li>
		<?php else: ?>
			<li><a href="<?php echo HTML::chars($page->url($i)) ?>"><?php echo $i ?></a></li>
		<?php endif ?>

	<?php endfor ?>

	<?php if ($next_page !== FALSE): ?>
		<li><a href="<?php echo HTML::chars($page->url($next_page)) ?>" rel="next"><?php echo __('Next') ?></a></li>
	<?php else: ?>
		<li class="active"><?php echo __('Next') ?></li>
	<?php endif ?>

	<?php if ($last_page !== FALSE): ?>
		<a href="<?php echo HTML::chars($page->url($last_page)) ?>" rel="last"><?php echo __('Last') ?></a></li>
	<?php else: ?>
		<li class="active"><?php echo __('Last') ?></li>
	<?php endif ?>
</ul>