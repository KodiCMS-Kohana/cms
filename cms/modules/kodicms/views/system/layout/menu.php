<?php if($navigation !== NULL): ?>
<div id="main-menu-inner">
	<ul class="navigation">
		<?php foreach ($navigation->sections() as $section): ?>
		<li class="mm-dropdown">
			<a href="#">
				<?php if($section->icon): ?><?php echo UI::icon($section->icon); ?> <?php endif; ?>
				<span class="mm-text"><?php echo $section->name(); ?></span>
			</a>
			<ul>
				<?php foreach ( $section as $item ): ?>
				<li>
					<a href="<?php echo $item->url(); ?>">
						<?php if($item->icon): ?><?php echo UI::icon($item->icon); ?> <?php endif; ?>
						<span class="mm-text"><?php echo $item->name(); ?></span>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
		</li>
		<?php endforeach; ?>
		
	</ul>
</div>
<?php endif; ?>