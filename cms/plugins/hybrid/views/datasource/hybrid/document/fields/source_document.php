<script>
	$(function() {
		function format(state) {
			return '<a target="_blank" href="/backend/hybrid/document/view?ds_id=<?php echo $field->from_ds; ?>&id='+state.id+'">' + state.text + '</a>';
		}

		$('input[name="<?php echo $field->name; ?>"]').select2({
			placeholder: __("Type first 1 chars to find documents"),
			minimumInputLength: 0,
			maximumSelectionSize: 1,
			multiple: false,
			formatSelection: format,
			escapeMarkup: function(m) { return m; },
			ajax: {
				url: Api.build_url('datasource/hybrid-document.find'),
				data: function(query, pageNumber, context) {
					return {
						key: query,
						<?php if(!empty($doc->id)): ?>id: <?php echo $doc->id; ?>,<?php endif; ?>
						doc_ds: <?php echo $field->from_ds; ?>,
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
							<?php if(!empty($doc->id)): ?>id: <?php echo $doc->id; ?>,<?php endif; ?>
							doc_ds: <?php echo $field->from_ds; ?>,
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
	})
</script>

<div class="form-group">
	<label class="control-label col-md-3"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="col-md-9">
		<div class="input-group">
			<?php echo Form::hidden($field->name, $value['id'], array(
				'id' => $field->name, 'class' => 'col-md-12'
			)); ?>

			<div class="input-group-btn">
				<?php if ( ! empty($value['id'])): ?>
				<?php echo UI::button(__('View'), array(
					'href' => Route::get('datasources')->uri(array(
						'directory' => 'hybrid',
						'controller' => 'document',
						'action' => 'view'
					)) . URL::query(array('ds_id' => $field->from_ds, 'id' => $value['id']), FALSE),
					'icon' => UI::icon('building'),
					'class' => 'btn-default popup fancybox.iframe',
					'data-target' => $field->name
				)); ?>
				<?php endif; ?>

				<?php echo UI::button(__('Create new'), array(
					'href' => Route::get('datasources')->uri(array(
						'directory' => 'hybrid',
						'controller' => 'document',
						'action' => 'create'
					)) . URL::query(array('ds_id' => $field->from_ds), FALSE),
					'icon' => UI::icon('building'),
					'class' => 'btn-default popup fancybox.iframe',
					'data-target' => $field->name
				)); ?>
			</div>
		</div>
	</div>

	<?php if($field->hint): ?>
	<p class="help-block"><?php echo $field->hint; ?></p>
	<?php endif; ?>
</div>
