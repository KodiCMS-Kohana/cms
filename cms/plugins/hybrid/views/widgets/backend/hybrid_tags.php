<?php echo View::factory('widgets/backend/blocks/section', array(
	'widget' => $widget
)); ?>

<?php if( $widget->ds_id == 0 ): ?>
<div class="note note-warning">
	<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need select hybrid section'); ?>
</div>
<?php else: ?>
<?php echo View::factory('widgets/backend/blocks/tag_fields', array(
	'widget' => $widget
)); ?>

<?php echo View::factory('widgets/backend/tags_cloud', array(
	'widget' => $widget
)); ?>
<?php endif; ?>
