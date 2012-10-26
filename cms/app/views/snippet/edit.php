<?php $uri = ($action == 'edit') 
	? URL::site('snippet/edit/'. $snippet->name) 
	: URL::site('snippet/add/' . $snippet->name); ?>

<?php echo Form::open($uri, array('id' => 'snippetEditForm', 'class' => 'form-horizontal')); ?>

	<?php echo Form::hidden('token', Security::token()); ?>	
	<div class="title-block">
		<div class="control-group">
			<label class="control-label title" for="snippetEditNamelabel"><?php echo __('Snippet name'); ?></label>
			<div class="controls">
				<?php echo Form::input('name', $snippet->name, array(
					'class' => 'slug focus input-title', 'id' => 'snippetEditNamelabel',
					'tabindex'	=> 1
				)); ?>
			</div>
		</div>
	</div>

	<div class="title-content"><?php echo __('Content'); ?></div>
	<?php echo Form::textarea('content', $snippet->content, array(
			'id'			=> 'textarea_content',
			'tabindex'		=> 2,
		)); ?>

	<div class="form-actions">
		<?php echo UI::actions($page_name); ?>
	</div>
<?php echo Form::close(); ?>
<!--/#snippetEditForm-->