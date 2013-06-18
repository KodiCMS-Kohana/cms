<div class="widget-header widget-no-border-radius spoiler-toggle" data-spoiler=".spoiler-page-fields" data-hash="page-fields">
	<h4><?php echo __('Page fields'); ?> <?php echo UI::icon( 'chevron-down spoiler-toggle-icon' ); ?></h4>
</div>

<div class="widget-content widget-no-border-radius spoiler spoiler-page-fields">
	<?php if(empty($page->id)): ?>
	<h4><?php echo __('Copy fields from'); ?></h4>
	<select name="fields[from_page_id]" class="span12">
		<option value="0"><?php echo __("Don't copy"); ?></option>
		<?php foreach ($pages as $p): ?>
		<option value="<?php echo($p['id']); ?>" <?php echo($p['id'] == $page->parent_id ? ' selected="selected"': ''); ?> ><?php echo str_repeat('- ', $p['level'] * 2).$p['title']; ?></option>
		<?php endforeach; ?>
	</select>
	<?php else: ?>
	<div class="well">
	<?php echo View::factory('page_fields/page/field', array(
		'field' => ORM::factory( 'page_field')
	)); ?>
	</div>
	<?php foreach($fields as $field): ?>
	<?php echo View::factory('page_fields/page/field', array(
		'field' => $field
	)); ?>
	<?php endforeach; ?>
	<?php endif; ?>
</div>