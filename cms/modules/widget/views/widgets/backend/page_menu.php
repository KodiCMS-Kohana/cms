<script type="text/javascript">
$(function() {
	$('#select_page_id').on('change', function() {
		show_field($(this));
	});
	
	show_field($('#select_page_id'));
})

function show_field($select) {
	if($select.val() == 0)
		$('#page_level_container').show();
	else
		$('#page_level_container').hide();
}
</script>

<div class="panel-body">
	<?php
		echo Bootstrap_Form_Element_Control_Group::factory(array(
			'element' => Bootstrap_Form_Element_Select::factory(array(
				'name' => 'page_id', 'options' => $select
			))
			->attributes('class', Bootstrap_Form_Element_Input::XXLARGE)
			->attributes('id', 'select_page_id')
			->selected($widget->page_id)
			->label(__('Page'))
		));
	?>

	<div class="form-group" id="page_level_container">
		<label class="control-label" for="page_level"><?php echo __('Select page level'); ?></label>
		<div class="controls">
			<?php echo Form::input('page_level',  $widget->page_level, array('id' => 'page_level', 'class' => 'input-mini')); ?>	
		</div>
	</div>
	
	<?php	
		echo Bootstrap_Form_Element_Control_Group::factory(array(
			'element' => Bootstrap_Form_Element_Checkbox::factory(array(
				'name' => 'match_all_paths', 'value' => 1
			))
			->checked($widget->match_all_paths == 1)
			->label(__('Match All Pages within Given Deepness'))
		));
		
		echo Bootstrap_Form_Element_Control_Group::factory(array(
			'element' => Bootstrap_Form_Element_Checkbox::factory(array(
				'name' => 'include_hidden', 'value' => 1
			))
			->checked($widget->include_hidden == 1)
			->label(__('Include hidden pages'))
		));
	?>
</div>
<div class="widget-header">
	<h4><?php echo __('Exclude pages'); ?></h4>
</div>
<div class="panel-body widget-nopad">
	<table class="table table-striped">
		<colgroup>
			<col width="30px" />
			<col />
		</colgroup>
		<tbody>
			<?php foreach($pages as $page): ?>
			<tr>
				<?php if($page['id'] > 1): ?>
				<td>
					<?php echo Form::checkbox('exclude[]', $page['id'], in_array($page['id'], $widget->exclude), array('id' => 'page'.$page['id'])); ?>
				</td>
				<th><label for="page<?php echo $page['id']; ?>"><?php echo str_repeat('&nbsp;', $page['level'] * 10) . $page['title']; ?></label></th>
				<?php else: ?>
				<td></td>
				<th><h4><?php echo $page['title']; ?></h4></th>
				<?php endif; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>