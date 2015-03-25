<script>
$(function() {
	function format(state) {
		if (!state.id) return state.text; // optgroup
		return "<i class='fa fa-" + state.id + " fa-fw fa-lg'/> " + state.text;
	}
	$("#icons").select2({
		formatResult: format,
		formatSelection: format,
		escapeMarkup: function(m) { return m; }
	});
});
</script>
<?php
$icons = Config::get('icons')->as_array();
echo Form::select('icon', array_unique($icons), $icon, array(
	'class' => 'form-control', 'id' => 'icons'
));