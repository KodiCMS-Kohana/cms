<div class="panel-body">
	<?php $fields = array('breadcrumb', 'meta_title', 'meta_keywords', 'meta_description');
	foreach ($fields as $field): ?>
	<div class="form-group">
		<?php echo $page->label($field, array('class' => 'control-label col-md-3')); ?>
		<div class="col-md-9">
			<?php echo $page->field($field, array(
				'class' => 'form-control',
				'prefix' => 'page'
			)); ?>
		</div>
	</div>
	<?php endforeach; ?>

	<hr class="panel-wide" />

	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __( 'Robots' ); ?></label>
		<div class="col-md-4">
			<?php echo Form::select( 'page[robots]', Model_Page::robots(), $page->robots); ?>
		</div>
	</div>
</div>

<?php Observer::notify( 'view_page_edit_meta', $page ); ?>