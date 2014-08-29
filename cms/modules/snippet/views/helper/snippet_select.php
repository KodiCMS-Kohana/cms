<script type="text/javascript">
$(function() {
	$('#snippet-select').change(function() {
		var $option = $('option:selected', this);
		if($option.val() == 0)
			$('#EditTemplateButton').hide();
		else
			$('#EditTemplateButton')
				.show()
				.css({
					display: 'inline-block'
				})
				.attr('href', BASE_URL + '/snippet/edit/' + $option.val());
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
	$templates = array(
		__('--- Not set ---')
	);
	$snippets = Model_File_Snippet::find_all();

	foreach ($snippets as $snippet)
	{
		$templates[$snippet->name] = $snippet->name;
	}
}

if (empty($template)) $template = NULL;
if (empty($default)) $default = NULL;
if (empty($select_name)) $select_name = 'template';

$hidden = empty($template) ? 'hidden' : '';
?>

<?php if ( ! empty($header)): ?>
<div class="panel-heading" >
	<span class="panel-title" data-icon="desktop"><?php echo $header; ?></h4>
</div>
<?php endif; ?>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Snippet'); ?></label>
		<div class="col-md-6">
			<?php echo Form::select('template', $templates, $template, array(
				'id' => 'snippet-select'
			)); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<?php if(ACL::check('snippet.edit')): ?>
			<?php  echo UI::button('<span class="visible-md-inline visible-lg-inline">' . __('Edit snippet') . '</span>', array(
				'href' => Route::get('backend')->uri(array(
					'controller' => 'snippet', 
					'action' => 'edit',
					'id' => $template
				)), 'icon' => UI::icon('edit'),
				'class' => 'popup fancybox.iframe btn-primary btn-sm'.$hidden, 
				'id' => 'EditTemplateButton'
			)); ?>
			<?php endif; ?>

			<?php if(ACL::check('snippet.add')): ?>
			<?php echo UI::button('<span class="visible-md-inline visible-lg-inline">' . __('Add snippet') . '</span>', array(
				'href' => Route::get('backend')->uri(array(
					'controller' => 'snippet', 
					'action' => 'add'
				)),
				'icon' => UI::icon('plus'),
				'class' => 'popup fancybox.iframe btn-success btn-sm',
				'id' => 'AddTemplateButton'
			)); ?>
			<?php endif; ?>

			<?php echo $default; ?>
		</div>
	</div>
</div>