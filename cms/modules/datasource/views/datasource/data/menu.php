<?php if(!empty($tree)): ?>
<div class="panel datasource-menu">
	<?php foreach ($tree as $section => $data): ?>
	<div class="panel-heading">
		<span class="panel-title" data-icon="<?php echo Datasource_Data_Manager::get_icon($section); ?>"><?php echo __(ucfirst($section)); ?></span>
	</div>
	<ul class="list-group">
	<?php foreach ($data as $id => $name): ?>
		<?php echo recurse_menu($id, $name, $ds_id, $section); ?>
	<?php endforeach; ?>
	</ul>
	<?php endforeach; ?>
	
	<div class="panel-footer">
		<div class="btn-group">
			<?php echo UI::button(__('Create section'), array(
				'href' => '#', 'class' => 'dropdown-toggle btn-success',
				'data-icon-append' => 'caret-down', 'data-toggle' => 'dropdown'
			)); ?>

			<ul class="dropdown-menu">
			<?php foreach (Datasource_Data_Manager::types() as $type => $title): ?>
				<?php if(ACL::check($ds_type.'.section.create')): ?>
				<li><?php echo HTML::anchor(Route::get('datasources')->uri(array(
						'controller' => 'section',
						'directory' => 'datasources',
						'action' => 'create',
						'id' => $type
					)), UI::icon(Datasource_Data_Manager::get_icon($section)) . ' ' . $title); ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
<?php endif; ?>

<?php
	function recurse_menu($id, $name, $ds_id, $section)
	{
		if (!ACL::check($section . $ds_id . '.section.view'))
		{
			return;
		}

		$result = '';
		$selected = ($id == $ds_id) ? 'active' : '';

		$title = $name['name'];
		$result .= '<li class="list-group-item ' . $selected . '">';
		$result .= HTML::anchor(Route::get('datasources')->uri(array(
			'controller' => 'data',
			'directory' => 'datasources',
		)) . URL::query(array('ds_id' => $id), FALSE), $title, array('class' => 'list-group-item-heading'));

		$result .= '<div class="btn-group pull-right">';
		if (ACL::check($section . $ds_id . '.section.edit'))
		{
			$attributes = array(
				'href' => Route::get('datasources')->uri(array(
					'controller' => 'section',
					'directory' => 'datasources',
					'action' => 'edit',
					'id' => $id
				)),
				'icon' => UI::icon('wrench'),
				'class' => 'btn-default btn-xs'
			);

			if ($selected == 'active')
			{
				$attributes['data-hotkeys'] = 'ctrl+e';
			}

			$result .= UI::button(NULL, $attributes);
		}

		if (ACL::check($section . $ds_id . '.section.remove'))
		{
			$attributes = array(
				'href' => Route::get('datasources')->uri(array(
					'controller' => 'section',
					'directory' => 'datasources',
					'action' => 'remove',
					'id' => $id
				)),
				'icon' => UI::icon('trash-o'),
				'class' => 'btn-danger btn-confirm btn-xs'
			);
			$result .= UI::button(NULL, $attributes);
		}

		$result .= '</div>';

		if (!empty($name['description']))
			$result .= '<p class="text-muted list-group-item-text">' . $name['description'] . '</p>';

		$result .= '</li>';

		return $result;
	}
?>