<?php if ( ! $page_part->is_protected() ): ?>
	<div id="pageEditPart-<?php echo $index; ?>" class="part" data-name="<?php echo $page_part->name; ?>">
		<input id="pageEditPartName-<?php echo ($index - 1); ?>" name="part[<?php echo ($index - 1); ?>][name]" type="hidden" value="<?php echo $page_part->name; ?>" />

		<?php if ( isset( $page_part->id ) ): ?>
			<input id="pageEditPartId-<?php echo ($index - 1); ?>" name="part[<?php echo ($index - 1); ?>][id]" type="hidden" value="<?php echo $page_part->id; ?>" />
		<?php endif; ?>

		<div class="widget-header widget-no-border-radius widget-inverse">
			<h4><?php echo $page_part->name; ?></h4>
			<div class="widget-options pull-right">
				<?php echo HTML::anchor( '#', UI::icon( 'cog icon-white' ), array( 'class' => 'part-options-button' ) ); ?>
			</div>
		</div>

		<div class="widget-content part-options">
			<div class="row-fluid">
				<div class="span4">
					<?php echo __( 'Filter' ); ?>
					<select class="item-filter" name="part[<?php echo ($index - 1); ?>][filter_id]" rel="<?php echo ($index - 1); ?>">
						<option value="">&ndash; <?php echo __( 'none' ); ?> &ndash;</option>
						<?php foreach ( Filter::findAll() as $filter ): ?> 
							<option value="<?php echo $filter; ?>" <?php echo( $page_part->filter_id == $filter ? ' selected="selected"' : ''); ?> ><?php echo Inflector::humanize( $filter ); ?></option>
						<?php endforeach; ?> 
					</select>
				</div>

				<?php if ( AuthUser::hasPermission( 'administrator,developer' ) ): ?>
				<div class="span4">
					<label class="checkbox ">
						<?php echo Form::checkbox( 'part[' . ($index - 1) . '][is_protected]', Model_Page_Part::PART_PROTECTED, (isset( $page_part->is_protected ) && $page_part->is_protected == Model_Page_Part::PART_PROTECTED ) ) . ' ' . __( 'Is protected' ); ?>
					</label>
				</div>
				<?php endif; ?>

				<?php if ( $page_part->name != 'body' ): ?>
				<div class="span4 align-right">
					<?php echo UI::button(__( 'Remove part :part_name', array( ':part_name' => $page_part->name ) ), array(
						'class' => 'item-remove btn btn-danger', 'icon' => UI::icon( 'trash icon-white' )
					) ); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="widget-content widget-no-border-radius widget-nopad part-textarea">
			<textarea id="pageEditPartContent-<?php echo ($index - 1); ?>" name="part[<?php echo ($index - 1); ?>][content]" tabindex="<?php echo (6 + $index); ?>"><?php echo htmlentities( $page_part->content, ENT_COMPAT, 'UTF-8' ); ?></textarea>

			<?php if ( $page_part->filter_id != '' ): ?>
			<script>
				jQuery(function(){
					cms.filters.switchOn( 'pageEditPartContent-<?php echo ($index - 1); ?>', '<?php echo $page_part->filter_id; ?>' );
				});
			</script>
			<?php endif; ?>
		</div>
	</div>
<?php else: ?>
	<div class="widget-content">
		<p class="lead"><?php echo __( 'Content of page part <b>:part_name</b> is protected from changes.', array( ':part_name' => $page_part->name ) ); ?></p>
	</div>
<?php endif; ?>