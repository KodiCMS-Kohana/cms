<?php if (!defined('CMS_ROOT')) die; ?>

<h1>
	<a href="<?php echo get_url('plugins'); ?>"><?php echo __('Plugins'); ?></a> &rarr;
	<?php echo __('TinyMCE settings'); ?>
</h1>

<div id="TMCES" class="box">
	<form id="TMCESForm" class="form" action="<?php echo get_url('plugin/tinymce/settings'); ?>" method="post">
		
		<h2 class="box-title"><?php echo __('Select editor buttons'); ?></h2>
		
		<div id="TMCESButtonsSets">
			<?php foreach ($buttons_sets as $i => $button_set): ?>
			<?php if(!empty($button_set)): ?>
			<h3><?php echo __('Panel') .' '. ($i+1); ?></h3>
			
			<ul>
				<?php foreach ($button_set as $button): ?>
				<?php if ($button != '|'): ?>
				<li title="<?php echo $button; ?>"><input id="TMCESButtonField-<?php echo $button; ?>" type="checkbox" name="buttons[]" value="<?php echo $button; ?>" <?php if (in_array($button, $selected_buttons)) echo('checked'); ?> > <label for="TMCESButtonField-<?php echo $button; ?>"><img src="images/e.gif" alt="" class="mce mce_<?php echo $button; ?>" /></label></li>
				<?php else: ?>
				<li class="separator"></li>
				<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			<?php endforeach; ?>
		</div>
		
		<h2 class="box-header"><?php echo __('Other settings'); ?></h2>
		
		<section>
			<label for="TMCESStylesheet"><?php echo __('Content stylesheet file'); ?> <em><?php echo __('Select absolute path to stylesheet file in public directory.'); ?></em></label>
			<span><input id="TMCESStylesheetField" class="input-text" type="text" name="setting[stylesheet]" maxlength="255" size="50" value="<?php echo isset($setting['stylesheet']) ? htmlentities($setting['stylesheet'], ENT_COMPAT, 'UTF-8'): ''; ?>" /> <button id="TMCESStylesheetSelectButton"><?php echo __('Select'); ?></button></span>
		</section>
		
		<div class="box-buttons">
			<button type="submit" name="commit"><img src="images/check.png" /> <?php echo __('Save setting'); ?></button>
			<?php echo __('or'); ?> <a href="<?php echo get_url('plugins'); ?>"><?php echo __('Cancel'); ?></a>
		</div>
	</form>
</div>