<?php if ($breadcrumbs->count()): ?>
<p class="breadcrumbs">
	<?php $i = 0; foreach ($breadcrumbs as $breadcrumb): ?>
		<?php if ($i > 0): ?>&raquo;<?php endif; ?>
		<?php if ($set_urls AND ! empty($breadcrumb->url)): ?>
		<a href="<?= $breadcrumb->url ?>" class="breadcrumb<?php if ($breadcrumb->active): ?><?php echo " ".$active_class; ?><?php endif; ?>">
		<?php endif; ?>

		<span class="breadcrumb<?php if ($breadcrumb->active): ?><?php echo " ".$active_class ?><?php endif; ?>"><?php echo $breadcrumb->name; ?></span>
		<?php if ($set_urls AND ! empty($breadcrumb->url)): ?>
		</a>
		<?php endif; ?>
	<?php $i++; endforeach; ?>
</p>
<?php endif; ?>