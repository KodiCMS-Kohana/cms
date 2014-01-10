<script>
	jQuery(function(){
		cms.filters.switchOn( '<?php echo $field->name; ?>', '<?php echo $field->filter; ?>', {height: 200});
	});
</script>
<?php
echo Form::textarea( $field->name, $value, array(
	'class' => 'input-plarge', 'id' => $field->name, 'data-height' => '265'
) );
?>