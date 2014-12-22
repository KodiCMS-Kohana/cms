<script id="part-body" type="text/template">
	<div class="part panel panel-darken no-margin-b" id="part<%=name %>">
		<div class="panel-heading padding-xs-vr form-inline">
			<div class="panel-heading-sortable-handler">
				<?php echo UI::icon('ellipsis-v fa-lg'); ?>
			</div>
			<span class="part-name panel-title"><%=name %></span>
			<input type="text" class="edit-name form-control input-sm" value="<%=name %>" />
			<% if ((is_protected == <?php echo Model_Page_Part::PART_PROTECTED; ?> && is_developer == 1) || is_protected == <?php echo Model_Page_Part::PART_NOT_PROTECTED; ?> ) { %>
			<div class="panel-heading-controls">
				<div class="btn-group">
					<?php echo Observer::notify('part_controls'); ?>
					<?php echo UI::button(UI::icon('edit'), array(
						'class' => 'part-rename btn-inverse btn-xs margin-r5')); ?>
					<?php if( ACL::check('page.parts')): ?>
					<?php echo UI::button(UI::icon('cog'), array(
						'class' => 'part-options-button btn-default btn-xs margin-r10')); ?>
					<?php endif; ?>
					<% if ( is_expanded == 0 ) { %>
					<?php echo UI::button(UI::icon('chevron-down'), array(
						'class' => 'part-minimize-button btn-inverse btn-xs')); ?>
					<% } else { %>		
					<?php echo UI::button(UI::icon('chevron-up'), array(
						'class' => 'part-minimize-button btn-inverse btn-xs')); ?>
					<% } %>
				</div>
			</div>
			<% } %>
		</div>
		
		<% if ((is_protected == <?php echo Model_Page_Part::PART_PROTECTED; ?> && is_developer == 1) || is_protected == <?php echo Model_Page_Part::PART_NOT_PROTECTED; ?> ) { %>
		<div class="part-options" style="display: none;">
			<div class="panel-body padding-sm form-inline">
			<?php if( ACL::check('page.parts')): ?>
				<div class="row">
					<div class="col-md-4 item-filter-cont">
						<label>
							<?php echo __( 'WYSIWYG' ); ?>&nbsp;&nbsp;&nbsp;
							<select class="item-filter" name="part_filter">
								<option value="">&ndash; <?php echo __( '--- none ---' ); ?> &ndash;</option>
								<?php foreach ( WYSIWYG::findAll() as $filter ): ?> 
									<option value="<?php echo $filter; ?>" <% if (filter_id == "<?php echo $filter; ?>") { print('selected="selected"')} %> ><?php echo Inflector::humanize( $filter ); ?></option>
								<?php endforeach; ?> 
							</select>
						</label>
					</div>			
					<div class="col-md-8 text-right">
						<?php echo Observer::notify('part_option'); ?>
						<% if ( is_developer == 1 ) { %>
						<label class="checkbox-inline">
							<input type="checkbox" name="is_protected" class="px is_protected" <% if (is_protected == <?php echo Model_Page_Part::PART_PROTECTED; ?>) { print('checked="checked"')} %>> <?php echo __( 'Is protected' ); ?>
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" name="is_indexable" class="px is_indexable" <% if (is_indexable == 1) { print('checked="checked"')} %>> <?php echo __( 'Is indexable' ); ?>
						</label>
						<% } %>
						<?php echo UI::button(__( 'Remove part :part_name', array( ':part_name' => '<%= name %>' ) ), array(
							'class' => 'item-remove btn-xs btn-danger', 'icon' => UI::icon( 'trash-o' )
						) ); ?>
					</div>
				</div>
				<?php else: ?>
				<select class="item-filter" name="part_filter">
					<option value="">&ndash; <?php echo __( '--- none ---' ); ?> &ndash;</option>
					<?php foreach ( WYSIWYG::findAll() as $filter ): ?> 
						<option value="<?php echo $filter; ?>" <% if (filter_id == "<?php echo $filter; ?>") { print('selected="selected"')} %> ><?php echo Inflector::humanize( $filter ); ?></option>
					<?php endforeach; ?> 
				</select>
				<?php endif; ?>
			</div>
			<hr class="no-margin" />
		</div>
		<% } %>
		

		<% if (is_protected == <?php echo Model_Page_Part::PART_PROTECTED; ?> && is_developer == 0 ) { %>
		<div class="panel-body">
			<p class="text-warning"><?php echo __( 'Content of page part :part_name is protected from changes.', array( ':part_name' => '<%= name %>' ) ); ?></p>
		</div>
		<% } else { %>
		<div class="part-textarea" <% if ( is_expanded == 0 ) { %>style="display:none;"<% } %>>
			<textarea class="form-control" rows="8" id="pageEditPartContent-<%= name %>" name="part_content[<%= id %>]"><%= content.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;") %></textarea>
		</div>
		<% } %>
	</div>
</script>