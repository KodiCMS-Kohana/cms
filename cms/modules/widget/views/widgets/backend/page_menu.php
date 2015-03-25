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
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Page'); ?></label>
		<div class="col-md-4">
			<?php echo Form::select('page_id',  $select, $widget->page_id, array('id' => 'select_page_id')); ?>	
		</div>
	</div>

	<div class="form-group" id="page_level_container">
		<label class="control-label col-md-3" for="page_level"><?php echo __('Select page level'); ?></label>
		<div class="col-md-2">
			<?php echo Form::input('page_level',  $widget->page_level, array('id' => 'page_level', 'class' => 'form-control')); ?>	
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label><?php echo Form::checkbox('match_all_paths', 1, $widget->match_all_paths == 1); ?> <?php echo __('Match All Pages within Given Deepness'); ?></label>
			</div>
			
			<div class="checkbox">
				<label><?php echo Form::checkbox('include_hidden', 1, $widget->include_hidden == 1); ?> <?php echo __('Include hidden pages'); ?></label>
			</div>
		</div>
	</div>
</div>
<div class="panel-heading">
	<span class="panel-title"><?php echo __('Exclude pages'); ?></span>
</div>
<table class="table table-noborder table-striped">
	<colgroup>
		<col width="50px" />
		<col width="450px" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th></th>
			<th></th>
			<th><?php echo __('Fetched widget'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($pages as $page): ?>
		<tr>
			<?php if($page['id'] > 1): ?>
			<td class="text-right">
				<?php echo Form::checkbox('exclude[]', $page['id'], in_array($page['id'], $widget->exclude), array('id' => 'page'.$page['id'])); ?>
			</td>
			<th><label for="page<?php echo $page['id']; ?>"><?php echo str_repeat('&nbsp;', $page['level'] * 10) . $page['title']; ?></label></th>
			<?php else: ?>
			<td></td>
			<th><?php echo $page['title']; ?></th>
			<?php endif; ?>
			<td>
				<?php
					$widgets = Widget_Manager::get_related(array());

					if (isset($widgets[$widget->id])) unset($widgets[$widget->id]);

					if (!empty($widgets))
					{
						$widgets = array(__('--- Not set ---')) + $widgets;

						$selected = Arr::get($widget->fetched_widgets, $page['id']);

						echo Form::select('fetched_widgets['.$page['id'].']', $widgets, $selected, array(
							'class' => 'form-control'
						)); 
					}
				?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>