<?php if ($navigation !== NULL): ?>
<div id="main-menu-inner">
	<ul class="navigation">
		<?php foreach ($navigation->get_pages() as $item): ?>
		<li <?php if($item->is_active()): ?>class="active"<?php endif; ?>>
			<a href="<?php echo $item->url(); ?>">
				<?php if ($item->icon): ?><?php echo UI::icon($item->icon . ' menu-icon'); ?> <?php endif; ?>
				<span class="mm-text"><?php echo $item->name(); ?></span>
			</a>
		</li>
		<?php endforeach; ?>
		<?php foreach ($navigation->sections() as $section): ?>

		<?php if (count($section) > 0 OR count($section->sections()) > 0): ?>
		<li class="mm-dropdown <?php if($section->is_active()): ?>open<?php endif; ?>">
			<a href="#">
				<?php if ($section->icon): ?><?php echo UI::icon($section->icon . ' menu-icon'); ?> <?php endif; ?>
				<span class="mm-text"><?php echo $section->name(); ?></span>
			</a>
			<ul>
				<?php foreach ($section as $item): ?>
				<li <?php if ($item->is_active()): ?>class="active"<?php endif; ?>>
					<a href="<?php echo $item->url(); ?>">
						<?php if ($item->icon): ?><?php echo UI::icon($item->icon . ' menu-icon'); ?> <?php endif; ?>
						<span class="mm-text"><?php echo $item->name(); ?></span>
					</a>
				</li>
				<?php endforeach; ?>
				
				<?php foreach ($section->sections() as $sub_section ): ?>
				<?php if (!(count($sub_section) > 0)) continue; ?>
				<li class="mm-dropdown <?php if($section->is_active()): ?>open<?php endif; ?>">
					<a href="#">
						<?php if ($sub_section->icon): ?><?php echo UI::icon($sub_section->icon . ' menu-icon'); ?> <?php endif; ?>
						<span class="mm-text"><?php echo $sub_section->name(); ?></span>
					</a>
					
					<ul>
						<?php foreach ($sub_section as $sub_item): ?>
						<li <?php if ($sub_item->is_active()): ?>class="active"<?php endif; ?>>
							<a href="<?php echo $sub_item->url(); ?>">
								<?php if ($sub_item->icon): ?><?php echo UI::icon($sub_item->icon . ' menu-icon'); ?> <?php endif; ?>
								<span class="mm-text"><?php echo $sub_item->name(); ?></span>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
				</li>
				<?php endforeach; ?>
			</ul>
		</li>
		<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>