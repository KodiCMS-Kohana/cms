<div class="widget">
	<div class="widget-header">
		<h3><?php echo __('Update'); ?></h3>
	</div>
	<div class="widget-content">
		<?php if(Update::check_version() === Update::VERSION_OLD): ?>
		<h3><?php echo __('There is a new :cms_name version (:version)', array(':version' => Update::remote_version(), ':cms_name' => CMS_NAME)); ?></h3>
		<?php else: ?>
		<h3><?php echo __('You have the latest version of :cms_name', array(':cms_name' => CMS_NAME)); ?></h3>
		<?php endif; ?>
		
		<p><?php echo __('To update the system, you can download :archive and unzip it to a folder :folder', array(
			':archive' => Update::link('archive'),
			':folder' => CMSPATH
		)); ?></p>
		
		<p><?php echo __('If the repository has been cloned from Github, then use the command `git pull`'); ?></p>
		
		<div class="alert alert-warning">
			<i class="icon icon-lightbulb"></i> 
			<?php echo __('When you replace the files in the :cms_name do not forget to set permissions on folders `:cache_folder` and `:logs_folder`, and save the changes made ​​to the :cms_name core', array(
				':cms_name' => CMS_NAME,
				':cache_folder' => 'cms/cache',
				':logs_folder' => 'cms/logs'
			)); ?>
		</div>
		
		<div id="files"></div>
	</div>
</div>