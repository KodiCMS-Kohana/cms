<div class="panel dashboard-widget hybrid-headline-widget" data-id="<?php echo $widget->id; ?>">
	<div class="panel-heading">
		<span class="panel-title"><?php echo (empty($header) AND ! empty($section)) ? $section->name : $header; ?>&nbsp;</span>
		
		<div class="panel-heading-controls">
			<button type="button" class="btn btn-default btn-xs widget_settings"><?php echo UI::icon('cog'); ?></button>
			<button type="button" class="btn btn-default btn-xs remove_widget"><?php echo UI::icon('times'); ?></button>
		</div>
	</div>

	<?php if (!empty($section)): ?>
	<div class="panel-body no-padding">
		<table class="table-primary table table-striped">
			<tbody>
				<?php foreach ($docs as $id => $doc): ?>
				<tr data-id="<?php echo $id; ?>" class="<?php echo !$doc['published'] ? 'unpublished' : ''; ?>">
					<th>
						<strong>
						<?php echo HTML::anchor(Route::get('datasources')->uri(array(
							'controller' => 'document',
							'directory' => 'hybrid',
							'action' => 'view'
						)) . URL::query(array(
							'ds_id' => $section->id(), 'id' => $id
						)), $doc['header']); ?>
						</strong>
					</th>
					<td></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="panel-footer">
		<?php if (ACL::check($section->has_access('document.create', TRUE))):?>
		<?php echo UI::button(__('Create Document'), array(
			'href' => Route::get('datasources')->uri(array(
				'controller' => 'document',
				'directory' => $section->type(),
				'action' => 'create'
			)) . URL::query(array('ds_id' => $section->id())),
			'icon' => UI::icon('plus'),
//			'class' => 'btn-primary'
		)); ?>
		<?php endif; ?>
		
		<?php if (ACL::check($section->has_access_view())):?>
		<?php echo UI::button(__('Goto section'), array(
			'href' => Route::get('datasources')->uri(array(
				'controller' => 'data',
				'directory' => 'datasources',
			)) . URL::query(array('ds_id' => $section->id())),
			'icon' => UI::icon(Datasource_Data_Manager::get_icon($section->type())),
			'class' => 'btn-xs btn-inverse'
		)); ?>
		<?php endif; ?>
	</div>
	<?php else: ?>
	<div class="note note-warning">
		<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need select hybrid section'); ?>
	</div>
	<?php endif; ?>
</div>

<script type="text/javascript">
$(function(){
	$('.hybrid-headline-widget[data-id="<?php echo $widget->id; ?>"]').on('resize_stop', function(e, gridster, ui) {
		updateScroll();
	});
	
	initScroll();
});

function initScroll() {
	$('.hybrid-headline-widget[data-id="<?php echo $widget->id; ?>"] .panel-body').slimScroll({
		height: calculate_body_height
	});
}

function updateScroll() {
	$('.hybrid-headline-widget[data-id="<?php echo $widget->id; ?>"] .panel-body')
		.slimScroll({destroy: true});

	initScroll();
}

function calculate_body_height() {
	var $cont = $('.hybrid-headline-widget[data-id="<?php echo $widget->id; ?>"]');
	var heading = $cont.find('.panel-heading');
	var footer = $cont.find('.panel-footer');
	var h = $cont.innerHeight() - heading.innerHeight() - footer.innerHeight();
	return h-5;
}
</script>