<footer class="margin-sm-vr">
	<div class="panel no-margin-b">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-8 text-muted">
					&copy; 2012<?php echo (date('Y') > 2012) ? ' - ' . date('Y') : ''; ?> <?php echo HTML::anchor(CMS_SITE, CMS_NAME) ?> v<?php echo CMS_VERSION; ?>
					&nbsp;&nbsp;&HorizontalLine;&nbsp;&nbsp;
					<?php echo __('Powered by :framework v:version :codename', array(
						':framework' => HTML::anchor('http://kohanaframework.org/', 'Kohana'),
						':version' => Kohana::VERSION, 
						':codename' => Kohana::CODENAME)); ?>
					&nbsp;&nbsp;&HorizontalLine;&nbsp;&nbsp;
					<?php echo __('Admin Theme :name', array(
						':name' => HTML::anchor('https://wrapbootstrap.com/theme/pixeladmin-premium-admin-theme-WB07403R9', 'PixelAdmin'))); ?>
				</div>
				<div class="col-md-4 text-right hidden-sm hidden-xs text-muted">
					<?php echo __('Thank you for using :site', array(':site' => HTML::anchor(CMS_SITE, CMS_NAME))); ?>
				</div>
			</div>
		</div>
	</div>
</footer>