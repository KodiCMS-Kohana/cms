<?php

// TODO: clean up code/solution
$pagetmp = Flash::get('page');
$parttmp = Flash::get('page_parts');
$tagstmp = Flash::get('page_tag');

if (!empty($pagetmp) AND !empty($parttmp) AND !empty($tagstmp))
{
	$page = $pagetmp;
	$page_parts = $parttmp;
	$tags = $tagstmp;
}
?>

<script type="text/javascript">
	var PAGE_ID = <?php echo (int) $page->id; ?>;

	<?php if($action == 'add'): ?>
	$(function() {
		$('#pageEditMetaMoreButton').click();
	})

	<?php endif; ?>
</script>


<div id="pageEdit">
	<?php echo Form::open($action == 'add' ? 'page/add/'.$parent_id : 'page/edit/'.$page->id, array(
		'id' => 'pageEditForm', 'class' => 'form-horizontal', 'method' => Request::POST
	)); ?>
		<?php echo Form::hidden('token', Security::token()); ?>
		<?php if (!empty($parent_id)): ?>
		<?php echo Form::hidden('page[parent_id]', $parent_id); ?>
		<?php endif; ?>

		<div class="container-fluid">
			<div class="row-fluid">
				<div id="pageEdit" class="box span9">
					<div class="widget widget-no-border-radius">
						<div id="pageEditMeta" class="widget-title">
							<?php echo View::factory('page/blocks/meta', array(
								'page' => $page,
								'action' => $action,
								'tags' => $tags
							)); ?>
						</div><!--/#pageEditMeta-->


						<?php echo View::factory('part/items'); ?>

						<?php Observer::notify('view_page_edit_plugins', array($page)); ?>

						<?php if($action != 'add'): ?>
						<div class="widget-content widget-no-border-radius">
							<?php if($page->title): ?>
							<?php if (isset($page->updated_on)): ?>
							<?php echo UI::label(__('Last updated by :anchor on :date', array(
								':anchor' => HTML::anchor('user/edit/' . $page->updated_by_id, $page->updated_by_name),
								':date' => date('D, j M Y', strtotime($page->updated_on))))); ?>
							<?php endif; ?>

							<?php echo HTML::anchor($page->getUrl(), UI::label(__('View page')), array(
								'class' => 'item-preview', 'target' => '_blankn'
							)); ?>
							<?php endif; ?>
						</div>
						<?php endif; ?>

						<div class="form-actions widget-footer">
							<?php echo UI::actions($page_name); ?>
						</div>
					</div>
				</div><!--/#pageEdit-->

				<div class="span3">
					<?php Observer::notify('view_page_edit_sidebar_before', array($page)); ?>

					<div class="outline">
						<div id="pageEditOptions" class="widget outline_inner">
							<div class="widget-header">
								<h3><?php echo __('Page options'); ?></h3>
							</div>

							<div class="widget-content">

								<label><?php echo __('Layout'); ?></label>
								<select name="page[layout_file]" class="span12">
									<option value="">&ndash; <?php echo __('inherit'); ?> &ndash;</option>
									<?php foreach ($layouts as $layout): ?>
									<option value="<?php echo($layout->name); ?>" <?php echo($layout->name == $page->layout_file ? ' selected="selected"': ''); ?> ><?php echo $layout->name; ?></option>
									<?php endforeach; ?>
								</select>

								<label><?php echo __('Type'); ?></label>
								<select name="page[behavior_id]" class="span12">
									<option value=""<?php if ($page->behavior_id == '') echo ' selected="selected"'; ?>>&ndash; <?php echo __('none'); ?> &ndash;</option>
									<?php foreach ($behaviors as $behavior): ?>
									<option value="<?php echo $behavior; ?>"<?php if ($page->behavior_id == $behavior) echo ' selected="selected"'; ?>><?php echo Inflector::humanize($behavior); ?></option>
									<?php endforeach; ?>
								</select>

								<?php if(AuthUser::hasPermission(array('administrator','developer')) && ($action == 'add' || ($action == 'edit' && isset($page->id) && $page->id != 1))): ?>
								<label><?php echo __('Status'); ?></label>

								<?php echo Form::select('page[status_id]', Model_Page::statuses(), $page->status_id, array(
									'class' => 'span12'
								)); ?>
								<?php endif; ?>

								<?php if($action == 'add' || ($action == 'edit' && isset($page->id) && $page->id != 1)): ?>
								<label><?php echo __('Published date'); ?></label>
								<input type="text" name="page[published_on]" value="<?php echo $page->published_on; ?>" maxlength="20"  class="span12"/>
								<?php endif; ?>

								<?php if (AuthUser::hasPermission(array('administrator','developer'))): ?>
								<label><?php echo __('Needs login'); ?></label>
								<?php echo Form::select('page[needs_login]', Model_Page::logins(), $page->needs_login, array(
									'class' => 'span12'
								)); ?>
								<?php endif; ?>

								<?php if (AuthUser::hasPermission(array('administrator','developer'))): ?>
								<label><?php echo __('Users roles that can edit page'); ?></label>
								<select name="page_permissions[]" multiple size="4" class="span12">
									<?php foreach ($permissions as $permission): ?>
									<option value="<?php echo $permission->name; ?>" <?php echo(in_array($permission->name, $page_permissions) ? 'selected': ''); ?> ><?php echo ucfirst($permission->name); ?></option>
									<?php endforeach; ?>
								</select>
								<?php endif; ?>
							</div>

							<?php Observer::notify('view_page_edit_options', array($page)); ?>
						</div><!--/#pageEditOptions-->
					</div>

					<?php Observer::notify('view_page_edit_sidebar_after', array($page)); ?>
				</div><!--/#contentSidebar-->
			</div>
		</div>
	<?php echo Form::close(); ?>
</div>