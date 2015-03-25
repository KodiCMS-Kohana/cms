$(function() {
	$('input[data-ajax-url]').each(function() {
		var $self = $(this);
		var $url = $self.data('ajax-url');
		var $preload = $self.data('ajax-preload');

		if($preload === true) {
			$.ajax($url, {
				data: {
					id: DOCUMENT_ID,
					ds_id: SECTION_ID
				},
				dataType: 'json',
			}).done(function(resp) {
				if(resp.code == 200)
					$self.select2({
						data: resp.response
					});
			});
		} else {
			$self.select2({
				placeholder: __("Type first 1 chars to find documents"),
				minimumInputLength: 1,
				maximumSelectionSize: 1,
				multiple: false,
				ajax: {
					url: $url,
					data: function(query, pageNumber) {
						return {
							key: query,
							id: DOCUMENT_ID,
							ds_id: SECTION_ID
						}
					},
					dataType: 'json',
					quietMillis: 250,
					results: function (resp) {
						if(resp.code == 200)
							return {results: resp.response};
						else
							return {results: []}
					}
				},
				initSelection: function(element, callback) {
					var id = $(element).val();
					if (id !== "") {
						$.ajax($url, {
							data: {
								key: $self.val(),
								id: DOCUMENT_ID,
								ds_id: SECTION_ID
							},
							dataType: 'json',
						}).done(function(resp) {
							if(resp.code == 200)
								for(row in resp.response) {
									if(resp.response[row]['id'] == id){
										return callback(resp.response[row]);
									}
								}
							return false;
						});
					}
				}
			});
		}
	});
});