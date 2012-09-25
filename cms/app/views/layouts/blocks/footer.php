<div id="footer">
	<div class="container-fluid">
		<div class="row">
			<div class="span6">
				&copy; 2012<?php echo (date('Y') > 2012) ? ' - ' . date('Y') : ''; ?> <?php echo HTML::anchor( 'https://github.com/butschster/flexocms', CMS_NAME ) ?> v<?php echo CMS_VERSION; ?>
			</div>
			<div class="span6">
				<p>Powered by <?php echo HTML::anchor( 'http://kohanaframework.org/', 'Kohana' ) ?> v<?php echo Kohana::VERSION ?></p>
			</div>
		</div>
	</div>
</div>
