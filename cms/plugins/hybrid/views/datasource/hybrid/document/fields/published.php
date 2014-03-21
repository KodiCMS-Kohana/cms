<div id="doc-published">
	<label class="radio inline">
		<?php echo Form::radio('published', 1, $doc->published == 1); ?> <?php echo __('Published'); ?>
	</label>

	<label class="radio inline">
		<?php echo Form::radio('published', 0, $doc->published == 0); ?> <?php echo __('Unpublished'); ?>
	</label>
</div>