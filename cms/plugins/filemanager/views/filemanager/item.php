<?php 
	$url = 'filemanager/' . $file->getRelativePath();
	$params = array();
	if( ! $file->isDir() )
	{
		$url = 'filemanager/view/' . $file->getRelativePath();
		$params['class'] = 'popup fancybox.iframe';
	}
	
	
?>
<tr class="<?php echo $icon; ?>" data-path="<?php echo $file->getRelativePath(); ?>">
	<th><?php echo UI::icon( $icon ); ?> <?php echo HTML::anchor( $url , $file->getFilename(), $params ); ?></th>
	<td class="mtime"><?php echo Date::format($file->getMTime()); ?></td>
	<td class="size"><?php echo Text::bytes( $file->getSize() ); ?></td>
	<td class="perms"><?php echo UI::button($file->getPerms(), array(
		'icon' => UI::icon( 'cog' ), 'class' => 'btn btn-link changeperms'
	)); ?></td>
	<td class="actions">
		<?php
		if(!$file->isDir())
		{
			echo UI::button(NULL, array(
				'class' => 'btn btn-mini', 
				'href' => 'download/'.Download::secure_path( PUBLICPATH . $file->getRelativePath() ), 
				'icon' => UI::icon( 'download' )
			) );
		}; ?>
		
		<?php
		echo UI::button( NULL, array(
			'href' => 'filemanager/delete/' . $file->getRelativePath(), 'icon' => UI::icon( 'remove icon-white' ),
			'class' => 'btn btn-mini btn-confirm btn-danger'
		) );
		?>
	</td>
</tr>