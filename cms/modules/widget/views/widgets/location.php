<div class="panel">
	<?php echo Form::open(Request::current()->uri()); ?>
	
	<?php if (Request::initial()->query('type') != 'iframe'): ?>
	<div class="panel-heading">
		<h3 class="no-margin-vr">
			<small>&larr; <?php echo __('Back to widget settings:'); ?></small>
			<?php echo HTML::anchor(Route::get('backend')->uri(array(
			'controller' => 'widgets', 
			'action' => 'edit',
			'id' => $widget->id
			)), $widget->name); ?>
		</h3>
	</div>
	<?php endif; ?>

	<table class="table table-primary table-striped">
		<colgroup>
			<col width="300px" />
			<col width="100px" />
			<col width="20px" />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Layout block'); ?></th>
				<th><?php echo __('Widget weight'); ?></th>
				<th></th>
				<th><?php echo __('Page'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php echo recurse_pages($pages, 0, $layouts_blocks, $page_widgets, $pages_widgets); ?>
		</tbody>
	</table>
	<hr />
	<div class="panel-body no-padding-vr">
		<div class="form-group form-inline">
			<div class="input-group">
				<?php echo Form::input('select_for_all', NULL, array('class' => 'form-control')); ?>
				<div class="input-group-btn">
					<?php echo UI::button( __('Select for all pages'), array(
						'icon' => UI::icon('level-up fa-flip-horizontal'), 
						'class' => 'btn-default', 
						'id' => 'select_for_all'
					)); ?>
				</div>
			</div>
			
			<?php if (ACL::check('layout.rebuild')): ?>
			<?php echo UI::button(__('Rebuild blocks'), array(
				'icon' => UI::icon( 'refresh' ),
				'class' => 'btn-xs btn-danger',
				'data-api-url' => 'layout.rebuild',
				'data-method' => Request::POST
			)); ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="panel-footer form-actions">
		<?php echo UI::button( __('Save locations'), array(
			'icon' => UI::icon( 'check'), 
			'class' => 'btn-lg btn-primary',
			'data-hotkeys' => 'ctrl+s'
		)); ?>
	</div>
	<?php echo Form::close(); ?>
</div>

<?php 
function recurse_pages( $pages, $spaces = 0, $layouts_blocks = array(), $page_widgets = array(), $pages_widgets = array() ) 
{
	$data = '';
	foreach ($pages as $page)
	{
		// Блок
		$current_block = Arr::path($page_widgets, $page['id'].'.0');
		$current_position = Arr::path($page_widgets, $page['id'].'.1');
		
		$data .= '<tr data-id="'.$page['id'].'" data-parent-id="'.$page['parent_id'].'">';
		$data .= '<td>';
		if (!empty($page['childs']))
		{
			$data .= '<div class="input-group">';
		}
		$data .= Form::hidden('blocks['.$page['id'].'][name]', $current_block, array('class' => 'widget-blocks form-control', 'data-layout' => $page['layout_file']));
		if (!empty($page['childs']))
		{
			$data .= "<div class=\"input-group-btn\">" . Form::button(NULL, UI::icon('level-down'), array(
				'class' => 'set_to_inner_pages btn',
				'title' => __('Select to child pages')
			) ) . '</div></div>';
		}
		$data .= '</td><td>';
		$data .= Form::input('blocks[' . $page['id'] . '][position]', (int) $current_position, array('maxlength' => 4, 'size' => 4, 'class' => 'form-control text-right widget-position') );
		$data .= '</td><td></td>';
		
		if (ACL::check('page.edit'))
		{
			$data .= '<th>' . str_repeat("-&nbsp;", $spaces) . HTML::anchor(Route::get('backend')->uri(array(
				'controller' => 'page',
				'action' => 'edit',
				'id' => $page['id']
			)), $page['title']) . '</th>';
		}
		else
		{
			$data .= '<th>' . str_repeat("-&nbsp;", $spaces) . $page['title'] . '</th>';
		}
		
		$data .= '</tr>';
		
		if (!empty($page['childs']))
		{
			$data .= recurse_pages($page['childs'], $spaces + 5, $layouts_blocks, $page_widgets, $pages_widgets);
		}
	}
	return $data;
} 
?>