<script id="part-body" type="text/template">
	<div class="part" id="part<%=name %>">
		<div class="widget-header widget-no-border-radius widget-inverse">
			<h4 class="part-name"><%=name %></h4><input type="text" class="edit-name" value="<%=name %>" />
			<div class="widget-options pull-right">
				<?php echo UI::button(UI::icon( 'cog' ), array(
					'class' => 'part-options-button btn btn-mini')
				); ?>
				
				<?php echo UI::button(UI::icon( 'chevron-up icon-white' ), array(
					'class' => 'part-minimize-button btn btn-mini btn-inverse')
				); ?>
			</div>
		</div>

		<div class="widget-content part-options">
			<div class="row-fluid">
				<div class="span4 item-filter-cont">
					<?php echo __( 'Filter' ); ?>
					<select class="item-filter" name="part_filter">
						<option value="">&ndash; <?php echo __( 'none' ); ?> &ndash;</option>
						<?php foreach ( Filter::findAll() as $filter ): ?> 
							<option value="<?php echo $filter; ?>" <% if (filter_id == "<?php echo $filter; ?>") { print('selected="selected"')} %> ><?php echo Inflector::humanize( $filter ); ?></option>
						<?php endforeach; ?> 
					</select>
				</div>

				<?php if ( AuthUser::hasPermission( 'administrator,developer' ) ): ?>
				<div class="span4">
					<label class="checkbox ">
						<?php echo Form::checkbox( 'is_protected', NULL, FALSE, array(
							'class' => 'is_protected'
						) ) . ' ' . __( 'Is protected' ); ?>
					</label>
				</div>
				<?php endif; ?>


				<div class="span4 pull-right align-right">
					<?php echo UI::button(__( 'Remove part :part_name', array( ':part_name' => '<%= name %>' ) ), array(
						'class' => 'item-remove btn btn-mini btn-danger', 'icon' => UI::icon( 'trash icon-white' )
					) ); ?>
				</div>
			</div>
		</div>

		<div class="widget-content widget-no-border-radius widget-nopad part-textarea">
			<textarea id="pageEditPartContent-<%= name %>" name="part_content[<%= id %>]"><%= content %></textarea>
		</div>
	</div>
</script>