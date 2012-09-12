<?php if ( empty( $page_part->is_protected ) || $page_part->is_protected == PagePart::PART_NOT_PROTECTED || ($page_part->is_protected == PagePart::PART_PROTECTED && AuthUser::hasPermission( array( 'administrator', 'developer' ) )) ): ?>
	<div id="pageEditPart-<?php echo $index; ?>" rel="<?php echo $page_part->name; ?>" class="item">
		<input id="pageEditPartName-<?php echo ($index - 1); ?>" name="part[<?php echo ($index - 1); ?>][name]" type="hidden" value="<?php echo $page_part->name; ?>" />

		<?php if ( isset( $page_part->id ) ): ?>
			<input id="pageEditPartId-<?php echo ($index - 1); ?>" name="part[<?php echo ($index - 1); ?>][id]" type="hidden" value="<?php echo $page_part->id; ?>" />
		<?php endif; ?>

		<div class="item-title">
			<?php echo $page_part->name; ?>
			<?php echo HTML::anchor( '#', UI::icon( 'cog icon-white' ), array( 'class' => 'item-options-button' ) ); ?>
		</div>

		<div class="item-options form-inline">
			<?php echo __( 'Filter' ); ?>
			<select class="item-filter" name="part[<?php echo ($index - 1); ?>][filter_id]" rel="<?php echo ($index - 1); ?>">
				<option value="">&ndash; <?php echo __( 'none' ); ?> &ndash;</option>
				<?php foreach ( Filter::findAll() as $filter ): ?> 
					<option value="<?php echo $filter; ?>" <?php echo( $page_part->filter_id == $filter ? ' selected="selected"' : ''); ?> ><?php echo Inflector::humanize( $filter ); ?></option>
				<?php endforeach; ?> 
			</select>

			<?php if ( AuthUser::hasPermission( 'administrator,developer' ) ): ?>

			<label class="checkbox ">
				<?php echo Form::checkbox( 'part[' . ($index - 1) . '][is_protected]', PagePart::PART_PROTECTED, (isset( $page_part->is_protected ) && $page_part->is_protected == PagePart::PART_PROTECTED ) ) . ' ' . __( 'Is protected' ); ?>
			</label>
			<?php endif; ?>

			<?php if ( $page_part->name != 'body' ): ?>
				<?php echo UI::button(__( 'Remove part :part_name', array( ':part_name' => $page_part->name ) ), array(
					'class' => 'item-remove btn btn-danger', 'icon' => UI::icon( 'trash icon-white' )
				) ); ?>
			<?php endif; ?>
		</div>

		<div class="item-content">
			<textarea id="pageEditPartContent-<?php echo ($index - 1); ?>" name="part[<?php echo ($index - 1); ?>][content]" tabindex="<?php echo (6 + $index); ?>" spellcheck="false" wrap="off"><?php echo htmlentities( $page_part->content, ENT_COMPAT, 'UTF-8' ); ?></textarea>

			<?php if ( $page_part->filter_id != '' ): ?>
			<script>
				jQuery(function(){
					cms.filters.switchOn( 'pageEditPartContent-<?php echo ($index - 1); ?>', '<?php echo $page_part->filter_id; ?>' );
				});
			</script>
			<?php endif; ?>
		</div>
	</div><!--/#pageEditPart-->
<?php else: ?>
	<div class="item item-part-protected">
		<p><?php echo __( 'Content of page part <b>:part_name</b> is protected from changes.', array( ':part_name' => $page_part->name ) ); ?></p>
	</div>
<?php endif; ?>