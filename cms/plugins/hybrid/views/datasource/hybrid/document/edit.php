<script type="text/javascript">
<?php if(!$document->has_access_change()): ?>
$(function() {
	$(':input').attr('disabled', 'disabled');
});
<?php endif; ?>
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

		var cont = $('#' + target).show();
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
					canvas.width = 70 * ratio;
					canvas.height = 70;

				var ctx = canvas.getContext("2d");
					ctx.drawImage(img, 0, 0, canvas.width, canvas.height );

				cont.append($('<div class="thumbnail pull-left margin-xs-hr"><img src="'+canvas.toDataURL("image/jpeg", 1)+'" /></div>'));
			};

			FR.readAsDataURL( file );
		}
	});
	
	$('input[data-related-document]').each(function() {
		var $self = $(this);
		var $ds_id = $self.data('related-document');

		$self.select2({
			placeholder: __("Type first 1 chars to find documents"),
			minimumInputLength: 0,
			maximumSelectionSize: 1,
			multiple: false,
			formatSelection: function(state) {
				return '<a target="_blank" href="/backend/hybrid/document/view?ds_id=' + $ds_id + '>&id=' + state.id + '">' + state.text + '</a>';
			},
			escapeMarkup: function(m) { return m; },
			ajax: {
				url: Api.build_url('datasource/hybrid-document.find'),
				data: function(query, pageNumber, context) {
					return {
						key: query,
						<?php if(!empty($document->id)): ?>id: <?php echo $document->id; ?>,<?php endif; ?>
						doc_ds: $ds_id,
						is_array: false
					}
				},
				dataType: 'json',
				results: function (resp, page) {
					return {results: resp.response};
				}
			},
			initSelection: function(element, callback) {
				var id = $(element).val();
				if (id !== "") {
					$.ajax(Api.build_url('datasource/hybrid-document.find'), {
						data: {
							ids: [parseInt(id)],
							<?php if(!empty($document->id)): ?>id: <?php echo $document->id; ?>,<?php endif; ?>
							doc_ds: $ds_id,
							is_array: false
						},
						dataType: 'json',
					}).done(function(resp, page) {
						for(row in resp.response) {

							if(resp.response[row]['id'] == id){
								return callback(resp.response[row]);
							}
						}
					});
				}
			}
		});
	});
	
	$('input[data-related-array]').each(function() {
		var $self = $(this);
		var $ds_id = $self.data('related-array');
		
		$self.select2({
			placeholder: __("Type first 1 chars to find documents"),
			minimumInputLength: 1,
			multiple:true,
			formatSelection: function(state) {
				return '<a target="_blank" href="/backend/hybrid/document/view?ds_id=' + $ds_id + '&id=' + state.id + '">' + state.text + '</a>';
			},
			escapeMarkup: function(m) { return m; },
			ajax: {
				url: Api.build_url('datasource/hybrid-document.find'),
				data: function(query, pageNumber, context) {
					return {
						key: query,
						<?php if(!empty($document->id)): ?>id: <?php echo $document->id; ?>,<?php endif; ?>
						doc_ds: $ds_id,
						is_array: true
					}
				},
				dataType: 'json',
				results: function (resp, page) {
					return {results: resp.response};
				}
			},
			initSelection: function(element, callback) {
				var id = $(element).val(),
					ids = id.split(',');

				ids = _.map(ids, function(num){ return parseInt(num); });

				if (id !== "") {
					$.ajax(Api.build_url('datasource/hybrid-document.find'), {
						data: {
							ids: ids,
							<?php if(!empty($document->id)): ?>id: <?php echo $document->id; ?>,<?php endif; ?>
							doc_ds: $ds_id,
							is_array: true
						},
						dataType: 'json',
					}).done(function(resp, page) {
						selected = [];
						for(row in resp.response) {
							if(_.indexOf(ids, resp.response[row]['id']) >= 0){
								selected.push(resp.response[row]);
							}
						}
						callback(selected);
					});
				}
			}
		});
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

<?php if($document->has_access_change()): ?>
<?php echo Form::open(Route::get('datasources')->uri(array(
		'controller' => 'document',
		'directory' => $datasource->type(),
		'action' => 'post'
	)), array(
	'class' => 'form-horizontal panel', 'enctype' => 'multipart/form-data'
)); ?>
<?php echo Form::hidden('ds_id', $datasource->id()); ?>
<?php echo Form::hidden('id', $document->id); ?>
<?php echo Form::hidden('csrf', Security::token()); ?>
<?php else: ?>
<div class="form-horizontal panel">
<?php endif; ?>
<div class="panel-heading">
	<div class="form-group form-group-lg">
		<label class="<?php echo Arr::get($form, 'label_class'); ?>"><?php echo __('Header'); ?></label>
		<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
			<?php echo Form::input('header', $document->header, array(
				'class' => 'form-control slug-generator', 'data-slug' => '.from-header'
			)); ?>
		</div>

		<?php echo View::factory('datasource/hybrid/document/fields/published'); ?>
	</div>	
</div>
	
<div class="panel-toggler text-center panel-heading" data-target-spoiler=".spoiler-meta">
	<?php echo UI::icon( 'chevron-down panel-toggler-icon' ); ?> <span class="muted"><?php echo __('Metadata'); ?></span>
</div>
<div class="panel-spoiler spoiler-meta panel-body">
	<div class="form-group">
		<label class="<?php echo Arr::get($form, 'label_class'); ?>"><?php echo __('Meta title'); ?></label>
		<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
			<?php echo Form::input('meta_title', $document->meta_title, array(
				'class' => 'form-control'
			)); ?>
		</div>
	</div>
	<div class="form-group">
		<label class="<?php echo Arr::get($form, 'label_class'); ?>"><?php echo __('Meta keywords'); ?></label>
		<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
			<?php echo Form::input('meta_keywords', $document->meta_keywords, array(
				'class' => 'form-control'
			)); ?>
		</div>
	</div>
	<div class="form-group">
		<label class="<?php echo Arr::get($form, 'label_class'); ?>"><?php echo __('Meta description'); ?></label>
		<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
			<?php echo Form::textarea('meta_description', $document->meta_description, array(
				'class' => 'form-control', 'rows' => 2
			)); ?>
		</div>
	</div>
	<hr class="panel-wide" />
</div>
<?php if($datasource->template() !== NULL): ?>
<?php echo View_Front::factory($datasource->template(), array(
	'fields' => $fields
)); ?>
<?php elseif(!empty($fields)): ?>
<div class="panel-body">
	<?php foreach ($fields as $key => $field): ?>
	<?php echo $field->backend_template($document); ?>
	<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if($document->has_access_change()): ?>
<div class="form-actions panel-footer">
	<?php echo UI::actions(TRUE, Route::get('datasources')->uri(array(
		'controller' => 'data',
		'directory' => 'datasources'
	)) . URL::query(array('ds_id' => $datasource->id()), FALSE)); ?>
</div>
<?php echo Form::close(); ?>
<?php else: ?>
</div>
<?php endif; ?>