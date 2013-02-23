<?php echo Form::open(NULL, array('class' => 'form-horizontal')); ?>
<?php echo Form::hidden('id', $category->id); ?>
<?php echo Form::hidden('token', Security::token()); ?>

	<div class="widget">
		<div class="widget-title">
			<div class="control-group">
				<label class="control-label"><?php echo __('Category title'); ?></label>
				<div class="controls">
					<div class="row-fluid">
					<?php echo Form::input('title', $category->title, array(
						'class' => 'slug-generator focus span12'
					)); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="widget-content">
			<div class="control-group">
				<label class="control-label"><?php echo __('Category slug'); ?></label>
				<div class="controls">
					<div class="row-fluid">
					<?php echo Form::input('slug', $category->slug, array(
						'class' => 'slug input-large'
					)); ?>
					</div>
				</div>
			</div>
			
		</div>
		<div class="form-actions widget-footer">
			<?php echo UI::actions($page_name); ?>
		</div>
	</div>

<?php echo Form::close(); ?>