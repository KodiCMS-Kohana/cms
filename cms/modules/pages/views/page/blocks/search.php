<?php 
	echo Form::open('page/search', array('class' => 'form-inline form-search'));
	echo Form::hidden('token', Security::token()); 
?>
	<div class="input-group input-group-sm">
		<?php echo Form::input('search', NULL, array(
			'id' => 'page-seacrh-input', 
			'class' => 'form-control no-margin-b',
			'placeholder' => __('Find page')
		)); ?>
		
		<div class="input-group-btn">
			<?php echo UI::button(__('Search'), array(
				'icon' => UI::icon('search'), 
				'class' => 'btn-default'
			)); ?>
		</div>
	</div>
<?php echo Form::close(); ?>