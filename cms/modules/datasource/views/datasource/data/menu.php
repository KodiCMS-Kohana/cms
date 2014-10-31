<?php
$sections = Datasource_Data_Manager::types();
foreach ($sections as $type => $title)
{
	if (!ACL::check($type . '.section.create'))
	{
		unset($sections[$type]);
	}
}

foreach ($tree as $type => $data)
{
	foreach ($data as $id => $section)
	{
		if(array_key_exists($section->folder_id(), $folders))
		{
			$folders[$section->folder_id()]['sections'][$id] = $section;
			unset($tree[$type][$id]);
		}
	}
}

$folders_status = Model_User_Meta::get('datasource_folders', array());
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
		
		<br /><br />
		<?php echo UI::button(__('Create folder'), array(
			'href' => '#', 'class' => 'btn-default btn-xs create-folder-button'
		)); ?>
	</div>
	<?php endif; ?>
	
	<?php if(!empty($folders)): ?>
	<div class="folders-list">
	<?php foreach ($folders as $folder_id => $folder): ?>
		<div class="folder-container">
			<div class="mail-nav-header" data-type="folder" data-icon="folder-open-o" data-id="<?php echo $folder_id; ?>">
				<?php echo $folder['name']; ?>
				<?php echo UI::icon('trash', array('class' => 'pull-right text-danger remove-folder')); ?>
			</div>
	
			<?php if(!empty($folder['sections'])): ?>
			<ul class="sections" <?php if(Arr::get($folders_status, $folder_id) === FALSE): ?>style="display: none;"<?php endif; ?>>
			<?php foreach ($folder['sections'] as $id => $section): ?>
				<?php echo recurse_menu($id, $section, $datasource->id()); ?>
			<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<div class="sections-list">
	<?php if(!empty($tree)): ?>
		<?php foreach ($tree as $type => $data): ?>
		<?php if(empty($data)) continue; ?>

		<div class="mail-nav-header" data-type="section" data-icon="<?php echo Datasource_Data_Manager::get_icon($type); ?>">
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
		$result .= '<li class="' . $selected . '" data-id="' . $id . '">';
		$result .= HTML::anchor(Datasource_Section::uri('view', $id), $section->name . UI::icon('ellipsis-v fa-lg', array(
			'class' => 'pull-right section-draggable'
		)), array('data-icon' => $section->icon()));
		$result .= '</li>';

		return $result;
	}
?>