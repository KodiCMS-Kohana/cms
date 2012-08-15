<h1>
	<a href="<?php echo get_url('plugins'); ?>"><?php echo __('Plugins'); ?></a> &rarr;
	<?php echo __('Image resizing settings'); ?>
</h1>

<div id="IRSettings" class="box">
	
	<form id="IRSettingsForm" class="form" action="<?php echo get_url('plugin/image_resizing/settings'); ?>" method="post">
		
		<section>
			<label><?php echo __('Cache images sizes'); ?> <em><?php echo __('Images with this sizes will be cached in same directory.'); ?></em></label>
			<span id="IRSettingsCache">
				<?php if(!empty($setting['cache_sizes'])): ?>
				<?php foreach(unserialize($setting['cache_sizes']) as $size): ?>
				<i class="radio"><input id="IRSettingsCacheCkeckbox-<?php echo $size; ?>" type="checkbox" value="<?php echo $size; ?>" name="setting[cache_sizes][]" checked /> <label for="IRSettingsCacheCkeckbox-<?php echo $size; ?>"><?php echo $size; ?></label></i>
				<?php endforeach; ?>
				<?php endif; ?>
				<button id="IRSettingsCacheAddButton"><?php echo __('Add'); ?></button>
			</span>
		</section>
		
		<section>
			<label for="IRSettingsQualityField"><?php echo __('Resized images quality'); ?> <em><?php echo __('More is better, but a larger file.'); ?></em></label>
			<span>
				<select id="IRSettingsQualityField" name="setting[quality]">
					<?php for($i=100; $i>=10; $i-=10): ?>
					<option value="<?php echo $i; ?>" <?php if($setting['quality'] == $i) echo('selected'); ?> ><?php echo $i; ?></option>
					<?php endfor; ?>
				</select>
			</span>
		</section>
		
		<div class="box-buttons">
			<button type="submit" name="commit"><img src="images/check.png" /> <?php echo __('Save setting'); ?></button>
			<?php echo __('or'); ?> <a href="<?php echo get_url('plugins'); ?>"><?php echo __('Cancel'); ?></a>
		</div>
		
	</form>
	
</div><!--/#-->