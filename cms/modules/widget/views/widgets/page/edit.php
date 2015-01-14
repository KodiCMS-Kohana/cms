<?php if (ACL::check('widgets.index')): ?>
<div class="panel-heading panel-toggler" data-hotkeys="shift+w">
	<span class="panel-title" data-icon="cubes"><?php echo __('Widgets'); ?></h4>
</div>
<div class="panel-body panel-spoiler">
	<?php if (empty($page->id)): ?>
	<h4><?php echo __('Copy widgets from'); ?></h4>
	<select name="widgets[from_page_id]" class="col-md-12">
		<option value=""><?php echo __('--- Do not copy ---'); ?></option>
		<?php foreach ($pages as $p): ?>
		<option value="<?php echo($p['id']); ?>" <?php echo($p['id'] == $page->parent_id ? ' selected="selected"': ''); ?> ><?php echo str_repeat('- ', $p['level'] * 2).$p['title']; ?></option>
		<?php endforeach; ?>
	</select>
	<?php else: ?>
	
	<?php if (ACL::check('widgets.location')): ?>
	<a class="btn btn-success fancybox.ajax popup" href="/api-widget.list/<?php echo $page->id; ?>" id="addWidgetToPage"><i class="fa fa-plus"></i> <?php echo __( 'Add widget to page' ); ?></a>
	
	<?php if (ACL::check('layout.rebuild')): ?>
	<?php echo UI::button(__('Rebuild blocks'), array(
		'icon' => UI::icon( 'refresh' ),
		'class' => 'btn-inverse btn-xs',
		'data-api-url' => 'layout.rebuild',
		'data-method' => Request::POST
	)); ?>
	<?php endif; ?>
	<br /><br />
	<?php endif; ?>
	<table class="table table-hover" id="widget-list">
		<colgroup>
			<col />
			<col width="100px" />
			<col width="280px" />
		</colgroup>
		<tbody>
		<?php foreach ($widgets as $widget): ?>
		<?php echo View::factory( 'widgets/ajax/row', array(
			'widget' => $widget, 'page' => $page
		)); ?>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>
<?php endif; ?>