<?php if (ACL::check('page.custom_fields')): ?>
<div class="panel-heading panel-toggler" data-hash="page-fields" data-icon="tasks fa-lg">
	<span class="panel-title"><?php echo __('Page fields'); ?></span>
</div>
<div class="panel-body panel-spoiler spoiler-page-fields">
	<?php if (empty($page->id)): ?>
	<h4><?php echo __('Copy fields from'); ?></h4>
	<select name="fields[from_page_id]" class="col-md-12">
		<option value="0"><?php echo __("Don't copy"); ?></option>
		<?php foreach ($pages as $p): ?>
		<option value="<?php echo($p['id']); ?>" <?php echo($p['id'] == $page->parent_id ? ' selected="selected"': ''); ?> ><?php echo str_repeat('- ', $p['level'] * 2).$p['title']; ?></option>
		<?php endforeach; ?>
	</select>
	<?php else: ?>
	<div class="well">
		<div class="col-md-5 select-field-container" style='display:none;'>
			<?php echo Form::hidden('field_id', NULL, array(
				'disabled', 'class' => 'col-md-12', 'id' => 'select-page-field-container'
			)); ?>
		</div>
		<?php echo View::factory('page/fields/field', array(
			'field' => ORM::factory( 'page_field')
		)); ?>
		<hr />
		<button id="select-page-field" class="btn btn-default btn-sm"><?php echo __('Show field select'); ?></button>
	</div>
	<?php foreach ($fields as $field): ?>
	<?php echo View::factory('page/fields/field', array(
		'field' => $field
	)); ?>
	<?php endforeach; ?>
	<?php endif; ?>
</div>
<?php endif; ?>