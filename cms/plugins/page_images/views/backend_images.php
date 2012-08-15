<?php if (!defined('CMS_ROOT')) die; ?>

<div id="PIPlugin" rel="<?php echo $page_id; ?>" class="plugin">
	<ul id="PIList">
		<?php foreach( $images as $item ): ?>
		<?php echo new View('../../'.PLUGINS_DIR_NAME.'/page_images/views/_image_item', array('item'=>$item)); ?>
		<?php endforeach; ?>
	</ul>
	
	<button id="PIAddButton" role="button"><img src="images/add.png" /> <?php echo __('Add images'); ?></button>
</div>