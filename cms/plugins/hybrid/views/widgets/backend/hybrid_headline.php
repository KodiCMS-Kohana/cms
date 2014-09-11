<script type="text/javascript">
$(function() {
	var rs = $('input[name="sort_by_rand"]');
	rs.on('change', function() {
		sort_by_rand($(this))
	});
	
	sort_by_rand(rs)
});

function sort_by_rand($field) {
	if($field.is(':checked')) {
		$('#sorting_block').hide();
	} else {
		$('#sorting_block').show();
	}
}
</script>

<?php echo View::factory('widgets/backend/blocks/section', array(
	'widget' => $widget
)); ?>

<?php if( ! $widget->ds_id ): ?>
<div class="alert alert-warning alert-dark">
	<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need select hybrid section'); ?>
</div>
<?php else: ?>
<div class="panel-heading">
	<span class="panel-title"><?php echo __('Properties'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group form-inline">
		<label class="control-label col-md-3" for="doc_uri"><?php echo __('Document page (URI)'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input( 'doc_uri', $widget->doc_uri, array(
				'class' => 'form-control', 'id' => 'doc_uri'
			) ); ?>
		</div>
	</div>

	<div class="form-group form-inline">
		<label class="control-label col-md-3" for="doc_id"><?php echo __('Identificator field'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input( 'doc_id', $widget->doc_id, array(
				'class' => 'form-control', 'id' => 'doc_id'
			) ); ?>
			<p class="help-block">
				<?php echo __('Multiple fields specify separated by commas'); ?>
			</p>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label><?php echo Form::checkbox('throw_404', 1, $widget->throw_404); ?> <?php echo __('Generate error 404 when page has no content'); ?></label>
				<br />
				<label><?php echo Form::checkbox('only_published', 1, $widget->only_published); ?> <?php echo __('Show only published documents'); ?></label>
				<br />
				<label><?php echo Form::checkbox('sort_by_rand', 1, $widget->sort_by_rand); ?> <?php echo __('Select random documents'); ?></label>
			</div>
		</div>
	</div>
	
	<div class="form-group form-inline">
		<label class="control-label col-md-3" for="search_key"><?php echo __('Search key (Ctx)'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input( 'search_key', $widget->search_key, array(
				'class' => 'form-control', 'id' => 'search_key'
			) ); ?>
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