<div class="widget">
	<?php foreach ($widgets as $type => $_widgets): ?>
	<div class="widget-header">
		<h3><?php echo __($type); ?></h3>
	</div>
	<div class="widget-content">
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
</div>