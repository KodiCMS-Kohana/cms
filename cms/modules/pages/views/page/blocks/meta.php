	<div class="control-group">
		<?php echo $page->label('title', array('class' => 'control-label title')); ?>
		<div class="controls">
			<?php echo $page->field('title', array(
				'class' => 'input-title span12 slug-generator',
				'prefix' => 'page'
			)); ?>
		</div>
	</div>
</div>
<div class="spoiler-toggle-container panel-body-bg">
	<div class="spoiler-toggle text-center" data-spoiler=".spoiler-meta" hotkeys="shift+m">
		<?php echo UI::icon('chevron-down spoiler-toggle-icon'); ?> <span class="muted"><?php echo __('Metadata'); ?></span>
	</div>
	<div id="pageEditMetaMore" class="spoiler spoiler-meta">
		<br />

		<?php if ($action == 'add' || ($action == 'edit' AND $page->id != 1)): ?>
		<div class="control-group">
			<?php echo $page->label('slug', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $page->field('slug', array(
					'class' => 'span12 slug',
					'prefix' => 'page'
				)); ?>
			</div>
		</div>
		<?php endif; ?>
		
		<?php if ($page->id != 1): ?>
		<div class="control-group">
			<?php echo $page->label('use_redirect', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo Form::checkbox('page[use_redirect]', 1, (bool) $page->use_redirect); ?>
			</div>
		</div>
		
		<div class="control-group hidden" id="redirect-to-container">
			<?php echo $page->label('redirect_url', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $page->field('redirect_url', array(
					'class' => 'span12',
					'prefix' => 'page'
				)); ?>
			</div>
		</div>
		<hr />
		<?php endif; ?>
		
		<div id="meta-container">
			<?php $fields = array('breadcrumb', 'meta_title', 'meta_keywords', 'meta_description');
			foreach ($fields as $field): ?>
			<div class="control-group">
				<?php echo $page->label($field, array('class' => 'control-label')); ?>
				<div class="controls">
					<?php echo $page->field($field, array(
						'class' => 'span12',
						'prefix' => 'page'
					)); ?>
				</div>
			</div>
			<?php endforeach; ?>


			<hr />

			<div class="control-group">
				<label class="control-label" for="pageEditMetaRobotsField"><?php echo __( 'Robots' ); ?></label>
				<div class="controls">
					<?php echo Form::select( 'page[robots]', Model_Page::robots(), $page->robots, array(
						'id' => 'pageEditMetaRobotsField'
					) ); ?>
				</div>
			</div>
		</div>
		<?php Observer::notify( 'view_page_edit_meta', $page ); ?>
	</div>