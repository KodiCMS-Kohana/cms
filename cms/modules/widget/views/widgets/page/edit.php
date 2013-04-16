<div class="widget-content widget-no-border-radius">
<?php if(empty($page->id)): ?>
	<h4><?php echo __('Copy widgets from'); ?></h4>
	<select name="widgets[from_page_id]" class="span12">
		<?php foreach ($pages as $p): ?>
		<option value="<?php echo($p['id']); ?>" <?php echo($p['id'] == $page->parent_id ? ' selected="selected"': ''); ?> ><?php echo str_repeat('- ', $p['level'] * 2).$p['title']; ?></option>
		<?php endforeach; ?>
	</select>
<?php endif; ?>
</div>