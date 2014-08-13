<div class="control-group">
	<label class="control-label" for="page_tags"><?php echo __('Tags (separator: ":sep")', array(':sep' => Model_Tag::SEPARATOR)); ?></label>
	<div class="controls">
		<?php echo Form::textarea('page_tags', implode(Model_Tag::SEPARATOR, $tags), array(
			'class' => 'span12 tags', 'id' => 'page_tags'
		)); ?>
	</div>
</div>