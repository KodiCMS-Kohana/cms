<?php if(!defined('CMS_ROOT')) die;

/**
 * Flexo CMS - Content Management System. <http://flexo.up.dn.ua>
 * Copyright (C) 2008 Maslakov Alexander <jmas.ukraine@gmail.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Flexo CMS.
 *
 * Flexo CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Flexo CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Flexo CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Flexo CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * @package Flexo
 * @subpackage views
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */
 
?>
<h1><?php echo __('General setting'); ?></h1>
					
<div id="setting" class="box">
	
	<form id="settingForm" class="form" action="<?php echo get_url('setting'); ?>" method="post">
	
		<h2 class="box-title"><?php echo __('Site options'); ?></h2>
		
		<section>
			<label for="settingTitle"><?php echo __('Site title'); ?> <em><?php echo __('This text will be bresent at backend and can be used in themes.'); ?></em></label>
			<span><input id="settingTitle" class="input-text" type="text" name="setting[admin_title]" maxlength="255" size="50" value="<?php echo htmlentities(Setting::get('admin_title'), ENT_COMPAT, 'UTF-8'); ?>" /></span>
		</section>
		
		<section>
			<label for="settingTheme"><?php echo __('Backend theme'); ?> <em><?php echo __('This will change your backend interface theme.'); ?></em></label>
			<span>
				<select id="settingTheme" name="setting[theme]">
				<?php $current_theme = Setting::get('theme'); ?>
				<?php foreach( Setting::getThemes() as $code => $label ): ?>
					<option value="<?php echo $code; ?>" <?php if ($code == $current_theme) echo 'selected="selected"'; ?>><?php echo __($label); ?></option>
				<?php endforeach; ?>
				</select>
			</span>
		</section>
		
		<section>
			<label for="settingSection"><?php echo __('Default backend section'); ?> <em><?php echo __('This allows you to specify which section you will see by default after login.'); ?></em></label>
			<span>
				<select id="settingSection" name="setting[default_tab]">
				<?php $current_default_nav = Setting::get('default_tab');?>
					<?php foreach( Plugin::$nav as $name => $group ): ?>
						<optgroup label="<?php echo __($name); ?>">
						<?php foreach( $group->items as $item ): ?>
							<option value="<?php echo $item->uri; ?>" <?php if ($item->uri == $current_default_nav) echo 'selected="selected"'; ?> ><?php echo $item->name; ?></option>
						<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				</select>
			</span>
		</section>
		
		<h2 class="box-header"><?php echo __('Page options'); ?></h2>
		
		<section>
			<label><?php echo __('Default page status'); ?> <em><?php echo __('This status will be autoselected when page creating.'); ?></em></label>
			<span>
				<i class="radio"><input id="settingPageStatusDraft" type="radio" name="setting[default_status_id]" value="<?php echo Page::STATUS_DRAFT; ?>" <?php if (Setting::get('default_status_id') == FrontPage::STATUS_DRAFT) echo 'checked="checked"'; ?> /> <label for="settingPageStatusDraft"><?php echo __('Draft'); ?></label></i>
				<i class="radio"><input id="settingPageStatusPublished" type="radio" name="setting[default_status_id]" value="<?php echo Page::STATUS_PUBLISHED; ?>" <?php if (Setting::get('default_status_id') == FrontPage::STATUS_PUBLISHED) echo 'checked="checked"'; ?> /> <label for="settingPageStatusPublished"><?php echo __('Published'); ?></label></i>
			</span>
		</section>
		
		<section>
			<label for="settingPageFilter"><?php echo __('Default filter'); ?> <em><?php echo __('Only for filter in pages, <i>not</i> in snippets.'); ?></em></label>
			<span>
				<select id="settingPageFilter" name="setting[default_filter_id]">
					<?php $current_default_filter_id = Setting::get('default_filter_id'); ?>
					<option value="" <?php if( $current_default_filter_id == '' ) echo ('selected="selected"'); ?> >&ndash; <?php echo __('none'); ?> &ndash;</option>
					<?php foreach( $filters as $filter_id ): ?>
					<?php if( isset($loaded_filters[$filter_id]) ): ?>
					<option value="<?php echo $filter_id; ?>" <?php if( $filter_id == $current_default_filter_id ) echo ('selected="selected"'); ?> ><?php echo Inflector::humanize($filter_id); ?></option>
					<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</span>
		</section>
		
		<?php Observer::notify('view_setting_plugins'); ?>
		
		<div class="box-buttons">
			<button type="submit"><img src="images/check.png" /> <?php echo __('Save setting'); ?></button>
		</div>
		
	</form>
	
</div><!--/#setting-->