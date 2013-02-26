<script>
	var CATEGORY_ID = '<?php echo $category->id; ?>';
</script>

<div id="Photos" class="widget">
	
	<div class="widget-header">
		<?php echo UI::button(__('Upload photos'), array(
			'icon' => UI::icon( 'download-alt' ),
			'class' => 'btn btn-large', 'id' => 'photo-upload-button'
		)); ?>
		
		<?php echo Form::file('file', array('id' => 'photo-upload-form', 'multiple' => 'multiple')); ?>
		
		<?php echo UI::button(__('Add category'), array(
			'icon' => UI::icon( 'plus' ), 'id' => 'create-category'
		)); ?>
	</div>
	
	<div class="widget-content">
		<?php if(!empty($categories)): ?>
		<div class="thumbnails categories droppable pull-left">
			<?php foreach ($categories as $cat): ?>
				<?php echo View::factory('photos/category', array('category' => $cat)); ?>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	
		<div class="thumbnails sortable photos">
			<?php if(!empty($photos)): ?>
			<?php foreach ($photos as $photo): ?>
				<?php echo View::factory('photos/image', array('photo' => $photo, 'category' => $category)); ?>
			<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</div>