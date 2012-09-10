<?php defined('SYSPATH') or die('No direct access allowed.');

// TODO: clean up code/solution
$pagetmp = Flash::get('page');
$parttmp = Flash::get('page_parts');
$tagstmp = Flash::get('page_tag');

if ($pagetmp != null && !empty($pagetmp) && $parttmp != null && !empty($parttmp) && $tagstmp != null && !empty($tagstmp))
{
    $page = $pagetmp;
    $page_parts = $parttmp;
    $tags = $tagstmp;
}
?>

<?php if($page->title): ?>
<div class="page-header">
	<h1><?php echo __('Edit page ":page"', array(':page' => $page->title)); ?></h1> 
</div>
<?php endif; ?>

<div id="pageEdit">
	<form id="pageEditForm" class="form-horizontal" action="<?php echo ($action == 'add' ? URL::site('page/add/'.$parent_id) : URL::site('page/edit/'.$page->id)); ?>" method="post">
		
		<?php echo Form::hidden('token', Security::token()); ?>
		
		<?php if (!empty($parent_id)): ?>
		<input type="hidden" name="page[parent_id]" value="<?php echo $parent_id; ?>" />
		<?php endif; ?>

		<div class="container-fluid">
			<div class="row-fluid">
				<div id="pageEdit" class="box span9">
					<div id="pageEditMeta">
						<div id="pageEditMetaTitle">
							<div class="control-group">
								<label class="control-label title" for="pageEditMetaSlugField"><?php echo __('Page title'); ?></label>
								<div class="controls">
									<?php echo Form::input('page[title]', $page->title, array(
										'class' => 'input-plarge title', 'id' => 'pageEditMetaTitleField'
									)); ?>
								</div>
							</div>
							<?php echo HTML::anchor('#', HTML::icon( 'cog' ), array('id' => 'pageEditMetaMoreButton')); ?>
						</div>
						<div id="pageEditMetaMore">
							<fieldset>
								<?php if($action == 'add' || ($action == 'edit' && isset($page->id) && $page->id != 1)): ?>
								<div class="control-group">
									<label class="control-label" for="pageEditMetaSlugField"><?php echo __('Slug'); ?></label>
									<div class="controls">
										<?php echo Form::input('page[slug]', $page->slug, array(
											'class' => 'input-plarge', 'id' => 'pageEditMetaSlugField'
										)); ?>
									</div>
								</div>
								<?php endif; ?>
								
								<div class="control-group">
									<label class="control-label" for="pageEditMetaBreadcrumbField"><?php echo __('Breadcrumb'); ?></label>
									<div class="controls">
										<?php echo Form::input('page[breadcrumb]', $page->breadcrumb, array(
											'class' => 'input-plarge', 'id' => 'pageEditMetaBreadcrumbField'
										)); ?>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="pageEditMetaKeywordsField"><?php echo __('Keywords'); ?></label>
									<div class="controls">
										<?php echo Form::textarea('page[keywords]', $page->keywords, array(
											'class' => 'input-plarge', 'id' => 'pageEditMetaKeywordsField'
										)); ?>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="pageEditMetaDescriptionField"><?php echo __('Description'); ?></label>
									<div class="controls">
										<?php echo Form::textarea('page[description]', $page->description, array(
											'class' => 'input-plarge', 'id' => 'pageEditMetaDescriptionField'
										)); ?>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="pageEditMetaTagsField"><?php echo __('Tags'); ?></label>
									<div class="controls">
										<?php echo Form::textarea('page[tags]', join(', ', $tags), array(
											'class' => 'input-plarge', 'id' => 'pageEditMetaTagsField'
										)); ?>
									</div>
								</div>
							</fieldset>

							<?php Observer::notify('view_page_edit_meta', array($page)); ?>

							<?php if (isset($page->updated_on)): ?>
							<p id="pageEditLastUpdated"><?php echo HTML::label(__('Last updated by <a href=":link">:name</a> on :date', array(':link' => URL::site('user/edit/' . $page->updated_by_id), ':name' => $page->updated_by_name, ':date' => date('D, j M Y', strtotime($page->updated_on))))); ?>
							</p>
							<?php endif; ?>
						</div>
					</div><!--/#pageEditMeta-->
					
					
					<?php echo View::factory('page/blocks/parts', array(
						'page_parts' => $page_parts,
						'permissions' => $permissions
					)); ?>

					<?php Observer::notify('view_page_edit_plugins', array($page)); ?>

					<div class="form-actions">
						<?php echo Form::actions($page_name); ?>
					</div>

				</div><!--/#pageEdit-->

				<div id="contentSidebar" class="span3 well well-small">
					<div id="pageEditOptions" class="box">
						<h3><?php echo __('Page options'); ?></h3>

						<p>
							<label><?php echo __('Layout'); ?></label>
							<span>
								<select name="page[layout_file]" class="span12">
									<option value="">&ndash; <?php echo __('inherit'); ?> &ndash;</option>
									<?php foreach ($layouts as $layout): ?>
									<option value="<?php echo($layout->name); ?>" <?php echo($layout->name == $page->layout_file ? ' selected="selected"': ''); ?> ><?php echo $layout->name; ?></option>
									<?php endforeach; ?>
								</select>
							</span>
						</p>

						<p>
							<label><?php echo __('Type'); ?></label>
							<span>
								<select name="page[behavior_id]" class="span12">
									<option value=""<?php if ($page->behavior_id == '') echo ' selected="selected"'; ?>>&ndash; <?php echo __('none'); ?> &ndash;</option>
									<?php foreach ($behaviors as $behavior): ?>
									<option value="<?php echo $behavior; ?>"<?php if ($page->behavior_id == $behavior) echo ' selected="selected"'; ?>><?php echo Inflector::humanize($behavior); ?></option>
									<?php endforeach; ?>
								</select>
							</span>
						</p>

						<?php if(AuthUser::hasPermission(array('administrator','developer')) && ($action == 'add' || ($action == 'edit' && isset($page->id) && $page->id != 1))): ?>
						<p>
							<label><?php echo __('Status'); ?></label>
							<span>
								<select name="page[status_id]" class="span12">
									<option value="<?php echo Page::STATUS_DRAFT; ?>"<?php echo $page->status_id == Page::STATUS_DRAFT ? ' selected="selected"': ''; ?>><?php echo __('Draft'); ?></option>
									<option value="<?php echo Page::STATUS_REVIEWED; ?>"<?php echo $page->status_id == Page::STATUS_REVIEWED ? ' selected="selected"': ''; ?>><?php echo __('Reviewed'); ?></option>
									<option value="<?php echo Page::STATUS_PUBLISHED; ?>"<?php echo $page->status_id == Page::STATUS_PUBLISHED ? ' selected="selected"': ''; ?>><?php echo __('Published'); ?></option>
									<option value="<?php echo Page::STATUS_HIDDEN; ?>"<?php echo $page->status_id == Page::STATUS_HIDDEN ? ' selected="selected"': ''; ?>><?php echo __('Hidden'); ?></option>
								</select>
							</span>
						</p>
						<?php endif; ?>

						<?php if($action == 'add' || ($action == 'edit' && isset($page->id) && $page->id != 1)): ?>
						<p>
							<label><?php echo __('Published date'); ?></label>
							<span>
								<input type="text" name="page[published_on]" value="<?php echo $page->published_on; ?>" maxlength="20"  class="span12"/>
							</span>
						</p>
						<?php endif; ?>

						<?php if (AuthUser::hasPermission(array('administrator','developer'))): ?>
						<p>
							<label><?php echo __('Needs login'); ?></label>
							<span>
								<select name="page[needs_login]" class="span12">
									<option value="<?php echo Page::LOGIN_INHERIT; ?>"<?php echo $page->needs_login == Page::LOGIN_INHERIT ? ' selected="selected"': ''; ?>>&ndash; <?php echo __('inherit'); ?> &ndash;</option>
									<option value="<?php echo Page::LOGIN_NOT_REQUIRED; ?>"<?php echo $page->needs_login == Page::LOGIN_NOT_REQUIRED ? ' selected="selected"': ''; ?>><?php echo __('Not required'); ?></option>
									<option value="<?php echo Page::LOGIN_REQUIRED; ?>"<?php echo $page->needs_login == Page::LOGIN_REQUIRED ? ' selected="selected"': ''; ?>><?php echo __('Required'); ?></option>
								</select>
							</span>
						</p>
						<?php endif; ?>

						<?php if (AuthUser::hasPermission(array('administrator','developer'))): ?>
						<p>
							<label><?php echo __('Users roles that can edit page'); ?></label>
							<span>
								<select name="page_permissions[]" multiple size="4" class="span12">
									<?php foreach ($permissions as $permission): ?>
									<option value="<?php echo $permission->name; ?>" <?php echo(in_array($permission->name, $page_permissions) ? 'selected': ''); ?> ><?php echo ucfirst($permission->name); ?></option>
									<?php endforeach; ?>
								</select>
							</span>
						</p>
						<?php endif; ?>

						<?php Observer::notify('view_page_edit_options', array($page)); ?>
					</div><!--/#pageEditOptions-->

					<?php Observer::notify('view_page_edit_sidebar', array($page)); ?>
				</div><!--/#contentSidebar-->
			</div>
		</div>
	</form>
</div>