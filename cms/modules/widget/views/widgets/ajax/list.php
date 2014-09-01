<div class="panel">
	<?php if(count($widgets) > 0): ?>
	<?php foreach ($widgets as $type => $_widgets): ?>
	<div class="panel-heading">
		<span class="panel-title"><?php echo __($type); ?></span>
	</div>
	<div class="panel-body padding-sm">
		<?php foreach ($_widgets as $id => $widget): ?>
			<?php echo UI::button($widget->name, array(
				'icon' => UI::icon('tag'), 'data-id' => $id, 
				'class' => 'popup-widget-item btn-default'
			)); ?>
		<?php endforeach; ?>
	</div>
	<?php endforeach; ?>
	<?php else: ?>
	<h2><?php echo __('All widgets set to page'); ?></h2>
	<?php endif; ?>
</div>