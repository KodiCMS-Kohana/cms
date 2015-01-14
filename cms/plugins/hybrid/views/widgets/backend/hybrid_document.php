<?php echo View::factory('widgets/backend/blocks/section', array(
	'widget' => $widget
)); ?>

<?php if (!$widget->ds_id): ?>
<div class="alert alert-warning alert-dark">
	<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need select hybrid section'); ?>
</div>
<?php else: ?>
<div class="panel-heading">
	<span class="panel-title"><?php echo __('Properties'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="doc_id_field"><?php echo __('Document ID field'); ?></label>
		<div class="col-md-3">
			<?php echo Form::select('doc_id_field', $widget->get_doc_ids(), $widget->doc_id_field, array(
				'class' => 'input-xlarge'
			)); ?>
		</div>
	</div>

	<div class="form-group form-inline">
		<label class="control-label col-md-3" for="doc_id_ctx"><?php echo __('Document ID (Ctx)'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input( 'doc_id_ctx', $widget->doc_id_ctx, array(
				'class' => 'form-control', 'id' => 'doc_id_ctx'
			) ); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label><?php echo Form::checkbox('throw_404', 1, $widget->throw_404); ?> <?php echo __('Generate error 404 when page has no content'); ?></label>
				<br />
				<label><?php echo Form::checkbox('seo_information', 1, $widget->seo_information); ?> <?php echo __('Change meta headers'); ?></label>
				<br />
				<label><?php echo Form::checkbox('crumbs', 1, $widget->crumbs); ?> <?php echo __('Change bread crumbs'); ?></label>
			</div>
		</div>
	</div>
</div>

<?php echo View::factory('widgets/backend/blocks/fields', array(
	'widget' => $widget
)); ?>
<?php endif; ?>