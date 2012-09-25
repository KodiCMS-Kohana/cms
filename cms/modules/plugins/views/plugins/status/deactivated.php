<tr id="<?php echo $id; ?>" class="<?php echo Text::alternate('alt', ''); ?> deactivated">
	<th class="plugin-name">
		<h5>
			<?php echo $plugin->title; ?>
			<?php if (isset($plugin->author)): ?>
			<?php echo UI::label($plugin->author); ?>
			<?php endif; ?></td>
		</h5>
		<p class="text-info"><?php echo $plugin->description; ?></p>
	</th>
	<td class="plugin-version"><?php echo $plugin->version; ?></td>
	<td class="plugin-status">
		<?php echo UI::button(NULL, array(
			'class' => 'change-status btn btn-mini btn-success', 
			'data-status' => 'false', 
			'icon' => UI::icon( 'play-circle icon-white')
		)); ?>
	</td>
</tr>