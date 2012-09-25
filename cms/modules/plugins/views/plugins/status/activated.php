<tr id="<?php echo $id; ?>" class="<?php echo Text::alternate('alt', ''); ?> activated">
	<th class="plugin-name">
		<h5>
			<?php if($plugin->settings): ?>
			<?php echo UI::button($plugin->title, array(
				'icon' => UI::icon('cog'), 'href' => Url::site($plugin->id . '/settings/'),
			)); ?>
			<?php else: ?>
			<?php echo $plugin->title; ?>
			<?php endif; ?>
			
			<?php if (isset($plugin->author)): ?>
			<?php echo UI::label($plugin->author); ?>
			<?php endif; ?></td>
		</h5>

		<p class="muted"><?php echo $plugin->description; ?></p>
	</th>
	<td class="plugin-version"><?php echo $plugin->version; ?></td>
	<td class="plugin-status">
		<?php echo UI::button(NULL, array(
			'class' => 'change-status btn btn-mini btn-danger', 
			'data-status' => 'true', 
			'icon' => UI::icon( 'off icon-white')
		)); ?>
	</td>
</tr>