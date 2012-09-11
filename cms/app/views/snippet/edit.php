<?php
$uri = ($action == 'edit') ? URL::site('snippet/edit/'. $snippet->name) : URL::site('snippet/add/' . $snippet->name);
?>
<div class="page-header">
	<h1><?php echo __('Snippets'); ?></h1> 
</div>

<?php echo Form::open($uri, array('id' => 'snippetEditForm', 'class' => 'form-horizontal')); ?>

	<?php echo Form::hidden('token', Security::token()); ?>
		<div class="control-group">
			<label class="control-label title" for="snippetEditNamelabel"><?php echo __('Snippet name'); ?></label>
			<div class="controls">
				<?php echo Form::input('snippet[name]', $snippet->name, array(
					'class' => 'input-xlarge slug focus title', 'id' => 'snippetEditNamelabel',
					'tabindex'	=> 1
				)); ?>
			</div>
		</div>

		<?php echo Form::textarea('snippet[content]', $snippet->content, array(
				'id'			=> 'textarea_content',
				'tabindex'		=> 2,
			)); ?>

	<div class="form-actions">
		<?php echo UI::actions($page_name); ?>
	</div>
<?php echo Form::close(); ?>
<!--/#snippetEditForm-->