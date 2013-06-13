<?php 
	$_blocks = array(
		'----', 'PRE' => __('Before page render')
	);
	$_blocks += $blocks;
?>

<script>
	var LAYOUT_BLOCKS = <?php echo json_encode( $_blocks ); ?>;
</script>

<?php if(empty($page->id)): ?>
<div class="widget-content widget-no-border-radius" data-hash="widgets">
	<h4><?php echo __('Copy widgets from'); ?></h4>
	<select name="widgets[from_page_id]" class="span12">
		<?php foreach ($pages as $p): ?>
		<option value="<?php echo($p['id']); ?>" <?php echo($p['id'] == $page->parent_id ? ' selected="selected"': ''); ?> ><?php echo str_repeat('- ', $p['level'] * 2).$p['title']; ?></option>
		<?php endforeach; ?>
	</select>
</div>
<?php else: ?>
<div class="widget-header widget-no-border-radius spoiler-toggle" data-spoiler=".spoiler-widgets" data-hash="widgets">
	<h4><?php echo __('Widgets'); ?> <?php echo UI::icon( 'chevron-down spoiler-toggle-icon' ); ?></h4>
</div>

<div class="widget-content widget-no-border-radius spoiler spoiler-widgets">
	<a class="btn fancybox.ajax popup" href="/ajax-widget-list/<?php echo $page->id; ?>" id="addWidgetToPage"><i class="icon-plus"></i> <?php echo __( 'Add widget to page' ); ?></a>
	<br /><br />
	<table class="table table-hover" id="widget-list">
		<colgroup>
			<col />
			<col width="250px" />
		</colgroup>
		<tbody>
		<?php foreach($widgets as $widget): ?>
		<tr>
			<th>
				<?php echo HTML::anchor('widgets/edit/' . $widget->id, $widget->name, array('target' => 'blank')); ?>
				<?php if(!empty($widget->description)): ?>
				<p class="muted"><?php echo $widget->description; ?></p>
				<?php endif; ?>
			</th>
			<td>
				<?php
				echo Form::select('widget['.$widget->id.'][block]', $_blocks, $widget->block, array('class' => 'widget-select-block no-script')); 
				?>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endif; ?>
