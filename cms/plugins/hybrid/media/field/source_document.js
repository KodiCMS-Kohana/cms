$(function() {
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
						id: DOCUMENT_ID,
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
							id: DOCUMENT_ID,
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
});