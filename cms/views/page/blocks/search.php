<?php 
echo Form::open(URL::site('page/search'), array(
	'class' => 'form-search pull-right'
));
echo Form::hidden('token', Security::token()); ?>
	<div class="input-prepend">
		<span class="add-on">
		<?php echo HTML::icon('search'); ?></span><?php echo Form::input('search', NULL, array(
			'id' => 'pageMapSearchField', 
			'class' => 'input-medium search-query',
			'placeholder' => __('Find page')
		)); ?>
		</span>
	</div>
<?php echo Form::close(); ?>