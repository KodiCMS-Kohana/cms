<?php defined('SYSPATH') or die('No direct access allowed.');
$uri = ($action == 'edit') ? URL::site('admin/snippet/edit/'. $snippet->name) : URL::site('admin/snippet/add/' . $snippet->name);
?>
<div class="page-header">
	<h1><?php echo __('Snippets'); ?></h1> 
</div>

<ul class="breadcrumb">
	<li><a href="<?php echo URL::site('admin/snippet'); ?>"><?php echo __('Snippets'); ?></a> <span class="divider">/</span></li>
	<li class="active"><?php echo __(ucfirst($action).' snippet'); ?></li>
</ul>

<?php echo Form::open($uri, array('id' => 'snippetEditForm', 'class' => 'form-horizontal')); ?>
	<fieldset>
		<div class="control-group">
			<label class="control-label title" for="snippetEditNamelabel"><?php echo __('Snippet name'); ?></label>
			<div class="controls">
				<?php echo Form::input('snippet[name]', $snippet->name, array(
					'class' => 'input-xlarge slug focus title', 'id' => 'snippetEditNamelabel',
					'tabindex'	=> 1
				)); ?>
			</div>
		</div>


		<div id="tabbyArea">
			<?php echo Form::textarea('snippet[content]', $snippet->content, array(
					'class'			=> 'tabby',
					'tabindex'		=> 2,
					'spellcheck'	=> 'false',
					'wrap'			=> 'off'
				)); ?>
		</div>
	</fieldset>
	<div class="form-actions">
		<?php echo Form::actions($page_name); ?>
	</div>
<?php echo Form::close(); ?>
<!--/#snippetEditForm-->