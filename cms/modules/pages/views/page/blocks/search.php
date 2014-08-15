<?php 
	echo Form::open('page/search');
	echo Form::hidden('token', Security::token()); 
?>
	<div class="input-group input-group-sm">
		<?php echo Form::input('search', NULL, array(
			'id' => 'page-seacrh-input', 
			'class' => 'form-control',
			'placeholder' => __('Find page')
		)); ?>
		
		<span class="input-group-btn">
		<?php echo UI::button(__('Search'), array(
			'icon' => UI::icon('search')
		)); ?>
			</span>
	</div>
<?php echo Form::close(); ?>