<div class="panel">
	<?php if(count($widgets) > 0): ?>
	<?php foreach ($widgets as $type => $_widgets): ?>
	<div class="panel-heading">
		<h3><?php echo __($type); ?></h3>
	</div>
	<div class="panel-body">
		<ul class="inline" class="popup-widget-list">
		<?php foreach ($_widgets as $id => $widget): ?>
			<?php echo UI::button($widget->name, array(
				'icon' => UI::icon('tag'), 'data-id' => $id, 
				'class' => 'popup-widget-item btn'
			)); ?>
		<?php endforeach; ?>
		</ul>
	</div>
	<?php endforeach; ?>
	<?php else: ?>
	<h2><?php echo __('All widgets set to page'); ?></h2>
	<?php endif; ?>
</div>