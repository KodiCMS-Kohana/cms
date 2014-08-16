<script id="part-body" type="text/template">
	<div class="part panel panel-info panel-dark no-margin-b" id="part<%=name %>">
		<div class="panel-heading">
			<span class="<?php if( ACL::check('page.parts')): ?>part-name<?php endif; ?> panel-title" title="<?php echo __('Double click to edit part name.'); ?>"><%=name %></span><input type="text" class="edit-name" value="<%=name %>" />
			
			<% if ((is_protected == <?php echo Model_Page_Part::PART_PROTECTED; ?> && is_developer == 1) || is_protected == <?php echo Model_Page_Part::PART_NOT_PROTECTED; ?> ) { %>
			<div class="widget-options pull-right">
				<?php if( ACL::check('page.parts')): ?>
				<?php echo UI::button(UI::icon( 'cog' ), array(
					'class' => 'part-options-button btn btn-xs')
				); ?>
				<?php endif; ?>
				
				<% if ( is_expanded == 0 ) { %>
				<?php echo UI::button(UI::icon( 'chevron-down' ), array(
					'class' => 'part-minimize-button btn btn-xs btn-inverse')
				); ?>		
				<% } else { %>		
				<?php echo UI::button(UI::icon( 'chevron-up' ), array(
					'class' => 'part-minimize-button btn btn-xs btn-inverse')
				); ?>
				<% } %>
			</div>
			<% } %>
		</div>
		
		<% if ((is_protected == <?php echo Model_Page_Part::PART_PROTECTED; ?> && is_developer == 1) || is_protected == <?php echo Model_Page_Part::PART_NOT_PROTECTED; ?> ) { %>
		<div class="panel-body part-options form-inline" style="display: none;">
		<?php if( ACL::check('page.parts')): ?>
			<div class="row">
				<div class="col-md-4 item-filter-cont">
					<label><?php echo __( 'WYSIWYG' ); ?></label>
					<select class="item-filter" name="part_filter">
						<option value="">&ndash; <?php echo __( '--- none ---' ); ?> &ndash;</option>
						<?php foreach ( WYSIWYG::findAll() as $filter ): ?> 
							<option value="<?php echo $filter; ?>" <% if (filter_id == "<?php echo $filter; ?>") { print('selected="selected"')} %> ><?php echo Inflector::humanize( $filter ); ?></option>
						<?php endforeach; ?> 
					</select>
				</div>			
				<div class="col-md-4">
					<?php echo Observer::notify('part_option'); ?>
					<% if ( is_developer == 1 ) { %>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="is_protected" class="px is_protected" <% if (is_protected == <?php echo Model_Page_Part::PART_PROTECTED; ?>) { print('checked="checked"')} %>> <?php echo __( 'Is protected' ); ?>
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="is_indexable" class="px is_indexable" <% if (is_indexable == 1) { print('checked="checked"')} %>> <?php echo __( 'Is indexable' ); ?>
						</label>
					</div>
					<% } %>
					<?php echo UI::button(__( 'Remove part :part_name', array( ':part_name' => '<%= name %>' ) ), array(
						'class' => 'item-remove btn btn-xs btn-danger', 'icon' => UI::icon( 'trash-o' )
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