<?php if( ACL::check( 'widgets.index')): ?>
	
<?php 
	$_blocks = array(
		0 => '----', 'PRE' => __('Before page render')
	);
	$_blocks += $blocks;
?>

<script>
	var LAYOUT_BLOCKS = <?php echo json_encode( $_blocks ); ?>;
</script>
<div class="widget-header widget-no-border-radius spoiler-toggle" data-spoiler=".spoiler-widgets" data-hash="widgets">
	<h4><?php echo __('Widgets'); ?></h4>
</div>

<div class="widget-content widget-no-border-radius spoiler spoiler-widgets">
	<?php if(empty($page->id)): ?>
	<h4><?php echo __('Copy widgets from'); ?></h4>
	<select name="widgets[from_page_id]" class="span12">
		<?php foreach ($pages as $p): ?>
		<option value="<?php echo($p['id']); ?>" <?php echo($p['id'] == $page->parent_id ? ' selected="selected"': ''); ?> ><?php echo str_repeat('- ', $p['level'] * 2).$p['title']; ?></option>
		<?php endforeach; ?>
	</select>
	<?php else: ?>
	<a class="btn fancybox.ajax popup" href="/ajax-widget-list/<?php echo $page->id; ?>" id="addWidgetToPage"><i class="icon-plus"></i> <?php echo __( 'Add widget to page' ); ?></a>
	<br /><br />
	<table class="table table-hover" id="widget-list">
		<colgroup>
			<col />
			<col width="250px" />
		</colgroup>
		<tbody>
		<?php foreach($widgets as $widget): ?>
		<?php echo View::factory( 'widgets/ajax/row', array(
			'widget' => $widget
		)); ?>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>
<?php endif; ?>