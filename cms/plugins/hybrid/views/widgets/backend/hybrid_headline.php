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
<div class="note note-warning">
	<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need select hybrid section'); ?>
</div>
<?php else: ?>
<div class="panel-heading">
	<h4><?php echo __('Properties'); ?></h4>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="doc_uri"><?php echo __('Document page (URI)'); ?></label>
		<div class="controls">
			<?php echo Form::input( 'doc_uri', $widget->doc_uri, array(
				'class' => 'input-xlarge', 'id' => 'doc_uri'
			) ); ?>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="doc_id"><?php echo __('Identificator field'); ?></label>
		<div class="controls">
			<?php echo Form::input( 'doc_id', $widget->doc_id, array(
				'class' => 'input-xlarge', 'id' => 'doc_id'
			) ); ?>
			<span class="help-block">
				<?php echo __('Multiple fields specify separated by commas'); ?>
			</span>
		</div>
	</div>

	<div class="form-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('throw_404', 1, $widget->throw_404); ?> <?php echo __('Generate error 404 when page has no content'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('only_published', 1, $widget->only_published); ?> <?php echo __('Show only published documents'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('sort_by_rand', 1, $widget->sort_by_rand); ?> <?php echo __('Select random documents'); ?></label>
		</div>
	</div>
</div>

<div class="panel-body ">
	<?php
		echo Bootstrap_Form_Element_Control_Group::factory(array(
			'element' => Bootstrap_Form_Element_Input::factory(array(
				'name' => 'search_key', 'value' => $widget->search_key
			))
			->label(__('Search key (Ctx)'))
		));
	?>
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