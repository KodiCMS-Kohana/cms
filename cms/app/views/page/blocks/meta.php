<div id="pageEditMetaTitle">
	<div class="control-group">
		<label class="control-label" for="pageEditMetaTitleField"><?php echo __( 'Page title' ); ?></label>
		<div class="controls">
			<?php
			echo Form::input( 'page[title]', $page->title, array(
				'class' => 'span12', 'id' => 'pageEditMetaTitleField'
			) );
			?>
		</div>
	</div>
<?php echo HTML::anchor( '#', UI::icon( 'cog' ), array( 'id' => 'pageEditMetaMoreButton' ) ); ?>
</div>
<div id="pageEditMetaMore">
	<fieldset>
		<?php if ( $action == 'add' || ($action == 'edit' && isset( $page->id ) && $page->id != 1) ): ?>
			<div class="control-group">
				<label class="control-label" for="pageEditMetaSlugField"><?php echo __( 'Slug' ); ?></label>
				<div class="controls">
					<?php
					echo Form::input( 'page[slug]', $page->slug, array(
						'class' => 'span12', 'id' => 'pageEditMetaSlugField'
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
			<label class="control-label" for="pageEditMetaKeywordsField"><?php echo __( 'Keywords' ); ?></label>
			<div class="controls">
				<?php
				echo Form::textarea( 'page[keywords]', $page->keywords, array(
					'class' => 'span12', 'id' => 'pageEditMetaKeywordsField'
				) );
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="pageEditMetaDescriptionField"><?php echo __( 'Description' ); ?></label>
			<div class="controls">
				<?php
				echo Form::textarea( 'page[description]', $page->description, array(
					'class' => 'span12', 'id' => 'pageEditMetaDescriptionField'
				) );
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="pageEditMetaTagsField"><?php echo __( 'Tags (separator: ":sep")', array(':sep' => Tag::SEPARATOR) ); ?></label>
			<div class="controls">
				<?php 
				echo Form::textarea( 'page[tags]', implode(Tag::SEPARATOR, $tags ), array(
					'class' => 'span12', 'id' => 'pageEditMetaTagsField'
				) );
				?>
			</div>
		</div>
	</fieldset>

	<?php Observer::notify( 'view_page_edit_meta', array( $page ) ); ?>
</div>