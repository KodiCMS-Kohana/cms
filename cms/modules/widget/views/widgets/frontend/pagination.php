<div class="pagination">
	<ul>
	<?php if ($first_page !== FALSE): ?>
		<li><a href="<?php echo HTML::chars($pagination->url($first_page)) ?>" rel="first"><?php echo __('First') ?></a></li>
	<?php else: ?>
		<li class="disabled"><span><?php echo __('First') ?></span></li>
	<?php endif ?>

	<?php if ($previous_page !== FALSE): ?>
		<li><a href="<?php echo HTML::chars($pagination->url($previous_page)) ?>" rel="prev"><?php echo __('Previous') ?></a></li>
	<?php else: ?>
		<li class="disabled"><span><?php echo __('Previous') ?></span></li>
	<?php endif ?>

	<?php for ($i = 1; $i <= $total_pages; $i++): ?>

		<?php if ($i == $current_page): ?>
			<li class="active">
				<span><?php echo $i ?></span>
			</li>
		<?php else: ?>
			<li>
				<a href="<?php echo HTML::chars($pagination->url($i)) ?>"><?php echo $i ?></a>
			</li>
		<?php endif ?>

	<?php endfor ?>

	<?php if ($next_page !== FALSE): ?>
		<li><a href="<?php echo HTML::chars($pagination->url($next_page)) ?>" rel="next"><?php echo __('Next') ?></a></li>
	<?php else: ?>
		<li class="disabled"><span><?php echo __('Next') ?></span></li>
	<?php endif ?>

	<?php if ($last_page !== FALSE): ?>
		<li><a href="<?php echo HTML::chars($pagination->url($last_page)) ?>" rel="last"><?php echo __('Last') ?></a></li>
	<?php else: ?>
		<li class="disabled"><span><?php echo __('Last') ?></span></li>
	<?php endif ?>
	</ul>
</div>