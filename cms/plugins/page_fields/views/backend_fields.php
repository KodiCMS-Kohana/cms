<?php if (!defined('CMS_ROOT')) die; ?>

<div id="PFPlugin" rel="<?php echo $page_id; ?>" class="plugin">
	<ul id="PFList">
		<?php foreach( $fields as $item ): ?>
		<li rel="<?php echo $item->id; ?>">
			<label for="PFField-<?php echo $item->id; ?>"><?php echo $item->name; ?></label>
			<span><textarea id="PFField-<?php echo $item->id; ?>" class="pf-field-textarea" name="pf_fields[<?php echo $item->name; ?>]"><?php echo htmlentities($item->value, ENT_COMPAT, 'UTF-8'); ?></textarea></span>
			<?php if (AuthUser::hasPermission(array('administrator', 'developer'))): ?>
			<a class="pf-remove-link" href="javascript:;" title="<?php echo __('Remove'); ?>"><img src="images/remove.png" /></a>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
	
	<button id="PFAddButton" role="button"><img src="images/add.png" /> <?php echo __('Add field'); ?></button>
</div>