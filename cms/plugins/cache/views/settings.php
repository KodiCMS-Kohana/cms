<h1>
	<a href="<?php echo get_url('plugins'); ?>"><?php echo __('Plugins'); ?></a> &rarr;
	<?php echo __('Cache settings'); ?>
</h1>

<div id="CS" class="box">
	<form id="CSForm" class="form" action="<?php echo get_url('plugin/cache/settings'); ?>" method="post">
		
		<section>
			<label><?php echo __('Cache type'); ?> <em><?php echo __('Dynamic – cached only big SQL-queryes. Static – all HTML data (you should select pages for caching).'); ?></em></label>
			<span id="CSType">
				<i class="radio"><input id="CSTypeRadio-dynamic" type="checkbox" value="yes" name="setting[cache_dynamic]" <?php if (isset($setting['cache_dynamic']) && $setting['cache_dynamic'] == 'yes') echo('checked'); ?> /> <label for="CSTypeRadio-dynamic"><?php echo __('Dynamic (recommended)'); ?></label></i>
				<i class="radio"><input id="CSTypeRadio-static" type="checkbox" value="yes" name="setting[cache_static]" <?php if (isset($setting['cache_static']) && $setting['cache_static'] == 'yes') echo('checked'); ?> /> <label for="CSTypeRadio-static"><?php echo __('Static'); ?></label></i>
			</span>
		</section>
		
		<section>
			<label for="CSRemove"><?php echo __('Removing cache'); ?></label>
			<span>
				<button id="CSRemoveButton" rel="<?php echo get_url('plugin/cache/remove_cache'); ?>"><?php echo __('Remove cached data'); ?></button>
			</span>
		</section>
		
		<section>
			<label for="CSRemoveStatic"><?php echo __('Removing static cache automaticly'); ?> <em><?php echo __('When update or save page – all static cache will be removed automaticly.'); ?></em></label>
			<span>
				<i class="radio"><input id="CSRemoveStaticCheckbox" type="checkbox" value="yes" name="setting[cache_remove_static]" <?php if (isset($setting['cache_remove_static']) && $setting['cache_remove_static'] == 'yes') echo('checked'); ?> /> <label for="CSRemoveStaticCheckbox"><?php echo __('Remove static cache automaticly'); ?></label></i>
			</span>
		</section>
		
		<section>
			<label for="CSLifetime"><?php echo __('Cache life time'); ?> <em><?php echo __('Time when cache will be updated. Default: 24*60*60 = 86400 seconds.'); ?></em></label>
			<span>
				<input id="CSLifetime" type="text" name="setting[cache_lifetime]" value="<?php echo(isset($setting['cache_lifetime']) ? (int)$setting['cache_lifetime']: 86400); ?>" />
			</span>
		</section>
		
		<div class="box-buttons">
			<button type="submit" name="commit"><img src="images/check.png" /> <?php echo __('Save setting'); ?></button>
			<?php echo __('or'); ?> <a href="<?php echo get_url('plugins'); ?>"><?php echo __('Cancel'); ?></a>
		</div>
		
	</form>
</div>