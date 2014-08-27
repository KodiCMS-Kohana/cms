<?php echo View::factory('widgets/backend/blocks/section', array(
	'widget' => $widget
)); ?>

<?php if( ! $widget->ds_id ): ?>
<div class="note note-warning">
	<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need select hybrid section'); ?>
</div>
<?php else: ?>
<div class="panel-heading">
	<span class="panel-title"><?php echo __('Properties'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group form-inline">
		<label class="control-label col-md-3" for="height"><?php echo __('Widget height'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('height', $widget->height, array(
				'class' => 'form-control', 'id' => 'height', 'size' => 3
			)); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label><?php echo Form::checkbox('only_published', 1, $widget->only_published); ?> <?php echo __('Show only published documents'); ?></label>
			</div>
		</div>
	</div>
</div>

<?php echo View::factory('widgets/backend/blocks/fields', array(
	'widget' => $widget
)); ?>

<?php echo View::factory('widgets/backend/blocks/sorting', array(
	'ds_id' => $widget->ds_id,
	'doc_order' => $widget->doc_order
)); ?>

<?php echo View::factory('widgets/backend/blocks/filters', array(
	'widget' => $widget
)); ?>
<?php endif; ?>