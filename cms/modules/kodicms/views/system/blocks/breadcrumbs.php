<?php if($breadcrumbs->count() > 1):?>
<ul class="breadcrumb breadcrumb-page">
<?php $i = 1; foreach($breadcrumbs as $breadcrumb): ?>
	<li>
		<?php if( ! empty($breadcrumb->url) AND $i < $breadcrumbs->count()): ?>
		<?php echo HTML::anchor( $breadcrumb->url, $breadcrumb->name ); ?>
		<?php else: ?>
		<span><?php echo $breadcrumb->name; ?></span>
		<?php endif; ?>
	</li>
	<?php $i++; ?>
	<?php endforeach; ?>
</ul>
<?php endif; ?>