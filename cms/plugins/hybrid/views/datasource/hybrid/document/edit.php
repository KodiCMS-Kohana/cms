<script type="text/javascript">
<?php if(!Acl::check('hybrid'.$ds->id().'.document.edit')): ?>
$(function() {
	$('input,textarea,select').attr('disabled', 'disabled');
})
<?php endif; ?>
var API_FORM_ACTION = '/datasource/hybrid-document.<?php if($doc->loaded()): ?>update<?php else: ?>create<?php endif; ?>'; 

$(function() {
	$('.upload-input').FileInput({
		placeholder: __('Select file to upload')
	});
	
	$('body').on('post:backend:api:datasource:hybrid-document.create ', update_documents);
	$('body').on('put:backend:api:datasource:hybrid-document.create ', update_documents);
	$('.upload-input').on('change', function(e) {
		var target = $(this).data('target');
		var size = parseInt($(this).data('size'));

		if(size)
			var mb = (size/1048576).toFixed(2);

		if( ! target) return;

		var cont = $('#' + target).empty();
		for (var i = 0; i < this.files.length; i++) {
			var file = this.files.item(i);

			if(size && file.size > size) {
				cms.messages.error(__('Image :image more than :size MB', {':image': file.name, ':size': mb}))
				continue;
			}

			var FR = new FileReader();

			FR.onload = function(e) {
				var img = new Image();
					img.src = e.target.result;

				var ratio = img.width / img.height;

				var canvas = document.createElement("canvas");
					canvas.width = 50 * ratio;
					canvas.height = 50;

				var ctx = canvas.getContext("2d");
					ctx.drawImage(img, 0, 0, canvas.width, canvas.height );

				cont.append($('<img class="img-polaroid" src="'+canvas.toDataURL("image/jpeg", 1)+'" />'));
			};

			FR.readAsDataURL( file );
		}
	});
});

function update_documents(e, response) {
	var target_field = cms.popup_target.data('target');
	if( target_field && response.id) {
		var current_val = $('#'+target_field).select2("val");
		if(_.isArray(current_val))
			current_val.push(response.id);
		else
			current_val = response.id;

		$('#'+target_field).select2("val", current_val)
	}
}
</script>

<?php if(Acl::check('hybrid'.$ds->id().'.document.edit')): ?>
<?php echo Form::open(Request::current()->url() . URL::query(array('id' => $doc->id)), array(
	'class' => 'form-horizontal panel', 'enctype' => 'multipart/form-data'
)); ?>
<?php echo Form::hidden('ds_id', $ds->id()); ?>
<?php echo Form::hidden('id', $doc->id); ?>
<?php echo Form::hidden('csrf', Security::token()); ?>
<?php else: ?>
<div class="form-horizontal panel">
<?php endif; ?>
<div class="panel-heading">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3"><?php echo __('Header'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('header', $doc->header, array(
				'class' => 'form-control slug-generator', 'data-slug' => '.from-header'
			)); ?>
		</div>

		<div class="col-md-offset-3 col-md-9">
			<?php echo View::factory('datasource/hybrid/document/fields/published', array(
				'doc' => $doc
			)); ?>
		</div>	
	</div>	
</div>
	
<div class="panel-toggler text-center panel-heading" data-target-spoiler=".spoiler-meta">
	<?php echo UI::icon( 'chevron-down panel-toggler-icon' ); ?> <span class="muted"><?php echo __('Metadata'); ?></span>
</div>
<div class="panel-spoiler spoiler-meta panel-body">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Meta title'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('meta_title', $doc->meta_title, array(
				'class' => 'form-control'
			)); ?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Meta keywords'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('meta_keywords', $doc->meta_keywords, array(
				'class' => 'form-control'
			)); ?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Meta description'); ?></label>
		<div class="col-md-9">
			<?php echo Form::textarea('meta_description', $doc->meta_description, array(
				'class' => 'form-control', 'rows' => 2
			)); ?>
		</div>
	</div>
	<hr class="panel-wide" />
</div>
<?php if($ds->template() !== NULL): ?>
<?php echo View_Front::factory($ds->template(), array(
	'fields' => $fields,
	'doc' => $doc,
)); ?>
<?php elseif(!empty($fields)): ?>
<div class="panel-body">
	<?php foreach ($fields as $key => $field): ?>
	<?php echo $field->backend_template($doc); ?>
	<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if(Acl::check('hybrid'.$ds->id().'.document.edit')): ?>
<div class="form-actions panel-footer">
	<?php echo UI::actions(TRUE, Route::get('datasources')->uri(array(
		'controller' => 'data',
		'directory' => 'datasources'
	)) . URL::query(array('ds_id' => $ds->id()), FALSE)); ?>
</div>
<?php echo Form::close(); ?>
<?php else: ?>
</div>
<?php endif; ?>