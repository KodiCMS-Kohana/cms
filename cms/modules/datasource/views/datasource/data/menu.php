<?php if(!empty($tree)): ?>
<div class="navigation">
	<div class="compose-btn">
		<div class="btn-group">
			<?php echo UI::button(__('Create section'), array(
				'href' => '#', 'class' => 'dropdown-toggle btn-primary btn-labeled btn-block',
				'data-icon-append' => 'caret-down btn-label', 'data-toggle' => 'dropdown'
			)); ?>
			<ul class="dropdown-menu">
			<?php foreach (Datasource_Data_Manager::types() as $type => $title): ?>
				<?php if(ACL::check($type.'.section.create')): ?>
				<li>
					<?php echo HTML::anchor(Datasource_Section::uri('create', $type), $title, array(
						'data-icon' => Datasource_Data_Manager::get_icon($datasource->type())
					)); ?>
				</li>
				<?php endif; ?>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
	
	<?php foreach ($tree as $section => $data): ?>
	<div class="mail-nav-header" data-icon="<?php echo Datasource_Data_Manager::get_icon($section); ?>">
		<?php echo __(ucfirst($section)); ?>
	</div>
	<ul class="sections">
	<?php foreach ($data as $id => $name): ?>
		<?php echo recurse_menu($id, $name, $datasource->id(), $section); ?>
	<?php endforeach; ?>
	</ul>

	<?php endforeach; ?>
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
		$result .= '<li class="' . $selected . '">';
		$result .= HTML::anchor(Datasource_Section::uri('view', $id), $title);

		$result .= '</li>';

		return $result;
	}
?>