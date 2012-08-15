<script>
	var FILE_MANAGER_NOW_PATH = '<?php echo $now_path; ?>';
</script>

<h1>
	<?php echo __('File manager'); ?> &rarr;
	<?php if(!empty($now_path)): ?><a href="<?php echo get_url('plugin/file_manager/'.PUBLIC_DIR_NAME.'/'); ?>"><?php echo PUBLIC_DIR_NAME; ?></a><?php else: echo(PUBLIC_DIR_NAME); endif; ?>
	<?php $full_path = ''; $dirs = explode('/', $now_path); $dirs_count = count($dirs); ?>
	<?php for($i=0; $i<$dirs_count; ++$i): ?>
	<?php if(empty($dirs[$i])) continue; ?>
	/
	<?php if($i+1 != $dirs_count): ?>
	<a href="<?php echo get_url('plugin/file_manager/'.PUBLIC_DIR_NAME.'/'.$full_path.$dirs[$i]); ?>"><?php echo urldecode($dirs[$i]); ?></a>
	<?php else: ?>
	<?php echo urldecode($dirs[$i]); ?>
	<?php endif; ?>
	<?php $full_path .= $dirs[$i].'/'; endfor; ?>
</h1>

<div id="FMMap" class="box map">
	<div id="FMMapActions" class="box-actions">
		<button rel="<?php echo get_url('plugin/file_manager/upload'); ?>" id="FMMapUploadButton" class="button-image"><img src="images/add.png" /> <?php echo __('Upload files'); ?></button>
		<button rel="<?php echo get_url('plugin/file_manager/upload'); ?>" id="FMMapCFolderButton" class="button-image"><img src="images/add.png" /> <?php echo __('Create folder'); ?></button>
	</div>
	
	<div class="map-header">
		<span class="name"><?php echo __('File name'); ?></span>
		<span class="size"><?php echo __('Size'); ?></span>
		<span class="perm"><?php echo __('Permissions'); ?></span>
		<span class="actions"><?php echo __('Actions'); ?></span>
	</div>
	
	<ul id="FMMapItems" class="map-items">
		<?php if (!empty($now_path)): ?>
		<li>
			<div class="item">
				<span class="name"><img src="<?php echo PLUGINS_URL.'file_manager/images/folder-up.png'; ?>" /> <a href="<?php echo get_url('plugin/file_manager/'.PUBLIC_DIR_NAME.'/' .(!empty($now_path) ? substr($now_path, 0, strrpos($now_path, '/')): '')); ?>"><?php echo __('Level up'); ?></a></span>
			</div>
		</li>
		<?php endif; ?>
		<?php foreach ($files as $file): ?>
		<?php if(!$file->isDir() || $file->isDot()) continue; ?>
		<li>
			<div class="item">
				<span class="name"><img src="<?php echo PLUGINS_URL.'file_manager/images/folder.png'; ?>" /> <a href="<?php echo get_url('plugin/file_manager/'.PUBLIC_DIR_NAME.'/' .(!empty($now_path) ? $now_path.'/': ''). $file->getFilenameUTF8()); ?>"><?php echo $file->getFilenameUTF8(); ?></a></span>
				<span class="size"><?php echo (!$file->isDir() ? convert_size($file->getSize()): ''); ?></span>
				<span class="perm"><?php echo $file->getChmodPerms(); ?></span>
				<span class="actions">
					<button class="item-rename-button" rel='<?php echo json_encode(array('name' => $file->getFilenameUTF8(), 'chmod' => $file->getChmodPerms(), 'now_path' => $now_path, 'dir' => true)); ?>' title="<?php echo __('Rename'); ?>"><img src="images/pen.png" /></button>
					<button class="item-remove-button" rel="<?php echo get_url('plugin/file_manager/remove/' . (!empty($now_path) ? $now_path.'/': '') .$file->getFilenameUTF8()); ?>" title="<?php echo __('Remove'); ?>"><img src="images/remove.png" /></button>
				</span>
			</div>
		</li>
		<?php endforeach; ?>
		<?php foreach ($files as $file): ?>
		<?php if($file->isDir() || $file->isDot()) continue; ?>
		<li>
			<div class="item">
				<span class="name"><img src="<?php echo PLUGINS_URL.'file_manager/images/files/file'. (file_exists(PLUGINS_ROOT.DIRECTORY_SEPARATOR.'file_manager'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'file-'.$file->getExt().'.png') ? '-'.$file->getExt(): '').'.png'; ?>" /> <a href="<?php echo PUBLIC_URL. (!empty($now_path) ? $now_path.'/': '') .$file->getFilenameUTF8(); ?>" target="_blank"><?php echo $file->getFilenameUTF8(); ?></a></span>
				<span class="size"><?php echo (!$file->isDir() ? convert_size($file->getSize()): ''); ?></span>
				<span class="perm"><?php echo $file->getChmodPerms(); ?></span>
				<span class="actions">
					<button class="item-rename-button" rel='<?php echo json_encode(array('name' => $file->getFilenameUTF8(), 'chmod' => $file->getChmodPerms(), 'now_path' => $now_path, 'dir' => false)); ?>' title="<?php echo __('Rename'); ?>"><img src="images/pen.png" /></button>
					<button class="item-remove-button" rel="<?php echo get_url('plugin/file_manager/remove/' . (!empty($now_path) ? $now_path.'/': '') .$file->getFilenameUTF8()); ?>" title="<?php echo __('Remove'); ?>"><img src="images/remove.png" /></button>
				</span>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
</div><!--/#FMMap-->