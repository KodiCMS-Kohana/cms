	<div class="control-group">
		<label class="control-label title" for="pageEditMetaTitleField"><?php echo __( 'Page title' ); ?></label>
		<div class="controls">
			<?php
			echo Form::input( 'page[title]', $page->title, array(
				'class' => 'span12 slug-generator input-title'
			) );
			?>
		</div>
	</div>
</div>
<div class="spoiler-toggle-container widget-content-bg widget-no-border-radius">
	<div class="spoiler-toggle text-center" data-spoiler=".spoiler-meta">
		<?php echo UI::icon( 'chevron-down spoiler-toggle-icon' ); ?>
	</div>
	<div id="pageEditMetaMore" class="spoiler spoiler-meta">
	<br />
	<?php if ( $action == 'add' || ($action == 'edit' && isset( $page->id ) && $page->id != 1) ): ?>
		<div class="control-group">
			<label class="control-label" for="pageEditMetaSlugField"><?php echo __( 'Slug' ); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'page[slug]', $page->slug, array(
					'class' => 'span12 slug', 'id' => 'pageEditMetaSlugField'
				) );
				?>
			</div>
		</div>
	<?php endif; ?>

	<div class="control-group">
		<label class="control-label" for="pageEditMetaBreadcrumbField"><?php echo __( 'Breadcrumb' ); ?></label>
		<div class="controls">
			<?php
			echo Form::input( 'page[breadcrumb]', $page->breadcrumb, array(
				'class' => 'span12', 'id' => 'pageEditMetaBreadcrumbField'
			) );
			?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="pageEditMetaTitleField"><?php echo __( 'Meta title' ); ?></label>
		<div class="controls">
			<?php
			echo Form::input( 'page[meta_title]', $page->meta_title, array(
				'class' => 'span12', 'id' => 'pageEditMetaTitleField'
			) );
			?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="pageEditMetaKeywordsField"><?php echo __( 'Meta keywords' ); ?></label>
		<div class="controls">
			<?php
			echo Form::textarea( 'page[meta_keywords]', $page->meta_keywords, array(
				'class' => 'span12', 'id' => 'pageEditMetaKeywordsField'
			) );
			?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="pageEditMetaDescriptionField"><?php echo __( 'Meta description' ); ?></label>
		<div class="controls">
			<?php
			echo Form::textarea( 'page[meta_description]', $page->meta_description, array(
				'class' => 'span12', 'id' => 'pageEditMetaDescriptionField'
			) );
			?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="pageEditMetaTagsField"><?php echo __( 'Tags (separator: ":sep")', array(':sep' => Model_Tag::SEPARATOR) ); ?></label>
		<div class="controls">
			<?php 
			echo Form::textarea( 'page[tags]', implode(Model_Tag::SEPARATOR, $tags ), array(
				'class' => 'span12 tags', 'id' => 'pageEditMetaTagsField'
			) );
			?>
		</div>
	</div>
	<?php Observer::notify( 'view_page_edit_meta', array( $page ) ); ?>
</div>