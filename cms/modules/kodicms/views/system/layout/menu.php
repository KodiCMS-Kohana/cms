<?php if($navigation !== NULL): ?>
<div id="main-menu-inner">
	<ul class="navigation">
		<?php foreach ($navigation->sections() as $section): ?>
		<li class="mm-dropdown">
			<a href="#">
				<?php if($section->icon): ?><?php echo UI::icon($section->icon . ' menu-icon'); ?> <?php endif; ?>
				<span class="mm-text"><?php echo $section->name(); ?></span>
			</a>
			<ul>
				<?php foreach ( $section as $item ): ?>
				<li>
					<a href="/<?php echo $item->url(); ?>">
						<?php if($item->icon): ?><?php echo UI::icon($item->icon . ' menu-icon'); ?> <?php endif; ?>
						<span class="mm-text"><?php echo $item->name(); ?></span>
					</a>
				</li>
				<?php endforeach; ?>
				
				<?php if(count($section->sections()) > 0): ?>
				<?php foreach ( $section->sections() as $sub_section ): ?>
				<li class="mm-dropdown">
					<a href="#">
						<?php if($sub_section->icon): ?><?php echo UI::icon($sub_section->icon . ' menu-icon'); ?> <?php endif; ?>
						<span class="mm-text"><?php echo $sub_section->name(); ?></span>
					</a>
					
					<ul>
						<?php foreach ( $sub_section as $sub_item ): ?>
						<li>
							<a href="/<?php echo $sub_item->url(); ?>">
								<?php if($sub_item->icon): ?><?php echo UI::icon($sub_item->icon . ' menu-icon'); ?> <?php endif; ?>
								<span class="mm-text"><?php echo $sub_item->name(); ?></span>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
				</li>
				<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</li>
		<?php endforeach; ?>
		
	</ul>
</div>
<?php endif; ?>