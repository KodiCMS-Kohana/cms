<script type="text/javascript">
$(function() {
	$('body').on('change', '#snippet-select', function() {
		var $value = $(this).val();
		if($value == 0)
			$('#EditTemplateButton').hide();
		else
			$('#EditTemplateButton')
				.show()
				.css({display: 'inline-block'})
				.attr('href', BASE_URL + '/snippet/edit/' + $value);
	}).change();

	$('body').on('post:backend:api-snippet', update_snippets_list);
	$('body').on('put:backend:api-snippet', update_snippets_list);
});

function update_snippets_list(e, response) {
	var select = $('#snippet-select');
	
	select
		.append($('<option>', {value: response.name, text: response.name}))
		.select2('val', response.name)
		.change();
}
</script>

<?php

if (empty($templates))
{
	$templates = Model_File_Snippet::html_select();
}

if (empty($template)) $template = NULL;
if (empty($default)) $default = NULL;
if (empty($select_name)) $select_name = 'template';

$hidden = empty($template) ? 'hidden' : '';
?>

<?php if (!empty($header)): ?>
<div class="panel-heading <?php if (!empty($spoiler)): ?>panel-toggler<?php endif; ?>" <?php if (!empty($spoiler)): ?>data-target-spoiler=".<?php echo $spoiler; ?>"<?php endif; ?>>
	<span class="panel-title" data-icon="desktop"><?php echo $header; ?></h4>
</div>
<?php endif; ?>
<div class="panel-body <?php if (!empty($spoiler)): ?>panel-spoiler <?php echo $spoiler; ?><?php endif; ?>">
	<div class="form-group form-inline">
		<label class="control-label col-sm-2" data-icon="file-code-o"><?php echo __('Snippet'); ?></label>
		<div class="col-md-9">
			<div class="input-group">
				<?php echo Form::select('template', $templates, $template, array(
					'id' => 'snippet-select', 'class' => 'form-control', 'style' => 'width: 250px'
				)); ?>
				
				<div class="btn-group">
					<?php if (ACL::check('snippet.edit')): ?>
					<?php  echo UI::button(UI::hidden(__('Edit snippet'), array('md', 'sm', 'xs')), array(
						'href' => Route::get('backend')->uri(array(
							'controller' => 'snippet', 
							'action' => 'edit',
							'id' => $template
						)), 'icon' => UI::icon('edit'),
						'class' => 'popup fancybox.iframe btn-primary'.$hidden, 
						'id' => 'EditTemplateButton'
					)); ?>
					<?php endif; ?>

					<?php if (ACL::check('snippet.add')): ?>
					<?php echo UI::button(UI::hidden(__('Add snippet'), array('md', 'sm', 'xs')), array(
						'href' => Route::get('backend')->uri(array(
							'controller' => 'snippet', 
							'action' => 'add'
						)),
						'icon' => UI::icon('plus'),
						'class' => 'popup fancybox.iframe btn-success',
						'id' => 'AddTemplateButton'
					)); ?>
					<?php endif; ?>

					<?php echo $default; ?>
				</div>
			</div>
		</div>
	</div>
</div>