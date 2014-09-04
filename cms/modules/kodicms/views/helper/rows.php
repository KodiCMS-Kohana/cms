<?php 
if(empty($data))
	$data = array();

if(empty($container))
	$container = 'rows-container';

if(empty($field))
	$field = 'data';

$container = URL::title($container);
$field = URL::title($field, '_');

$slugify = ! isset($slugify) ? TRUE : (bool) $slugify;
?>

<script type="text/javascript">
	var ROWS_DATA = <?php echo json_encode($data); ?>;
	
	$(function() {
		var $container = $('#<?php echo $container; ?>');

		$container.on('click', '.add-row', function(e) {
			clone_row($container);
			e.preventDefault();
		});
		
		$container.on('click', '.remove-row', function(e) {
			$(this).closest('.row-helper').remove();
			e.preventDefault();
		});

		for(key in ROWS_DATA) {
			row = clone_row($container);
			row.find('.row-key').val(key);
			row.find('.row-value').val(ROWS_DATA[key]);
		}
	});
	
	function clone_row($container) {
		return $('.row-helper.hidden', $container)
			.clone()
			.removeClass('hidden')
			.appendTo($('.rows-container', $container))
			.find(':input')
			.removeAttr('disabled')
			.end();
	}
</script>

<div class="form-group" id="<?php echo $container; ?>">
	<?php if(!empty($label)): ?><label class="control-label col-md-3"><?php echo $label; ?></label><?php endif; ?>
	<div class="<?php if(!empty($label)): ?>col-xs-9<?php else: ?>col-xs-12<?php endif; ?>">
		<div class="row-helper hidden padding-xs-vr">
			<div class="input-group">
				<input type="text" name="<?php echo $field; ?>[key][]" disabled="disabled" class="form-control <?php if($slugify): ?>slug<?php endif; ?> row-key" data-separator="_" placeholder="<?php echo __('Key'); ?>">
				<span class="input-group-addon"> - </span>
				<input type="text" name="<?php echo $field; ?>[value][]" disabled="disabled" class="form-control row-value" placeholder="<?php echo __('Description'); ?>">
				<div class="input-group-btn">
					<?php echo Form::button('trash-row', UI::icon('trash-o'), array(
						'class' => 'btn btn-warning remove-row'
					)); ?>
				</div>
			</div>
		</div>
		<div class="rows-container"></div>
		<?php echo Form::button('add-row', UI::icon('plus'), array(
			'class' => 'add-row btn btn-primary', 'data-hotkeys' => 'ctrl+a'
		)); ?>
	</div>
</div>