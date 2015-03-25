<div class="<?php echo Arr::get($form, 'input_container_offset_class'); ?>">
	<div id="doc-published">
		<label class="radio radio-inline">
			<?php echo Form::radio('published', 1, $document->published == 1); ?> <?php echo __('Published'); ?>
		</label>

		<label class="radio radio-inline">
			<?php echo Form::radio('published', 0, $document->published == 0); ?> <?php echo __('Unpublished'); ?>
		</label>
	</div>
</div>	