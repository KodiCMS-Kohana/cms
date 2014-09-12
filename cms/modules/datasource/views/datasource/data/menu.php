<?php 
$sections = Datasource_Data_Manager::types();
	foreach ($sections as $type => $title)
	{
		if (!ACL::check($type . '.section.create'))
		{
			unset($sections[$type]);
		}
	}
?>

<div class="navigation">
	<?php if(!empty($sections)): ?>
	<div class="compose-btn">
		<div class="btn-group">
			<?php echo UI::button(__('Create section'), array(
				'href' => '#', 'class' => 'dropdown-toggle btn-primary btn-labeled btn-block',
				'data-icon-append' => 'caret-down btn-label', 'data-toggle' => 'dropdown'
			)); ?>
			<ul class="dropdown-menu">
			<?php foreach ($sections as $type => $title): ?>
				<li>
					<?php echo HTML::anchor(Datasource_Section::uri('create', $type), $title, array(
						'data-icon' => Datasource_Data_Manager::get_icon($datasource->type())
					)); ?>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if(!empty($tree)): ?>
	<?php foreach ($tree as $type => $data): ?>
		<div class="mail-nav-header" data-icon="<?php echo Datasource_Data_Manager::get_icon($type); ?>">
			<?php echo __(ucfirst($type)); ?>
		</div>
		<ul class="sections">
		<?php foreach ($data as $id => $section): ?>
			<?php echo recurse_menu($id, $section, $datasource->id()); ?>
		<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>
	<?php endif; ?>
</div>



<?php
	function recurse_menu($id, $section, $ds_id)
	{
		if (!$section->has_access_view())
		{
			return;
		}

		$result = '';
		$selected = ($id == $ds_id) ? 'active' : '';

		$title = $section->name;
		$result .= '<li class="' . $selected . '">';
		$result .= HTML::anchor(Datasource_Section::uri('view', $id), $section->name);

		$result .= '</li>';

		return $result;
	}
?>