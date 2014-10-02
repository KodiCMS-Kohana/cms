### 5.14.0

 * UPDATE `settings` SET  `name` = 'site_title' WHERE `name` = 'admin_title';

### 6.0.0

 * ALTER TABLE  `pages` ADD  `meta_title` VARCHAR( 255 ) NOT NULL DEFAULT  '' AFTER  `breadcrumb`;
 * ALTER TABLE  `pages` CHANGE  `keywords`  `meta_keywords` VARCHAR( 255 ) NOT NULL DEFAULT  '';
 * ALTER TABLE  `pages` CHANGE  `description`  `meta_description` TEXT;
 * ALTER TABLE  `user_profiles` ADD  `locale` VARCHAR( 10 ) NOT NULL DEFAULT  'en-us' AFTER  `name`;
 * DELETE FROM  `settings` WHERE `name` = 'default_locale';

<pre>
CREATE TABLE IF NOT EXISTS `TABLE_PREFIX_roles_permissions` (
  `role_id` int(5) unsigned NOT NULL,
  `action` varchar(255) NOT NULL,
  UNIQUE KEY `role_id` (`role_id`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `TABLE_PREFIX_roles_permissions` ADD CONSTRAINT `roles_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `TABLE_PREFIX_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
</pre>

### 6.4.20

 * Метод Model_Widget_Decorator::load_template_data изменен на backend_data. Необходимо переименовать в своих виджетах.
 * Если используются виджеты, наследуемые от Model_Widget_Decorator_Pagination, в backend шаблоне больше не нужны поля `list_offset` и `list_size`

### 6.5.21

 * ALTER TABLE  `pages` ADD  `robots` VARCHAR( 100 ) NOT NULL DEFAULT  'INDEX,FOLLOW' AFTER  `meta_description`;

### 7.7.21

	CREATE TABLE IF NOT EXISTS `config` (
		`group_name` varchar(128) NOT NULL,
		`config_key` varchar(128) NOT NULL,
		`config_value` text NOT NULL,
		PRIMARY KEY (`group_name`,`config_key`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

### 7.10.21

 * ALTER TABLE  `page_parts` ADD  `is_expanded` INT( 1 ) NOT NULL DEFAULT  '1';

### 8.0.0

	CREATE TABLE IF NOT EXISTS `logs` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`created_on` datetime NOT NULL,
		`user_id` int(11) unsigned DEFAULT NULL,
		`level` tinytext NOT NULL,
		`message` text NOT NULL,
		`additional` text NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

### 8.2.14

	* Класс Filter отвечающий за текстовые редакторы переименован в WYSIWYG

### 9.1.8

	* Немного изменился синтаксис конфига `sitemap.php`

	<pre>
		'Design' => array(
			array(
				'divider' => TRUE,
				'name' => __('Widgets'), 
				'url' => URL::backend('widgets'),
				'permissions' => 'widgets.index',
				'priority' => 300,
				'icon' => 'th-large'
			),
		)

		=>

		array(
			'name' => 'Design',
			'children' => array(
				array(
					'divider' => TRUE,
					'name' => __('Widgets'), 
					'url' => URL::backend('widgets'),
					'permissions' => 'widgets.index',
					'priority' => 300,
					'icon' => 'th-large'
				),
				array( // Subsection
					'name' => 'Subsection name',
					'children' => array(
						array(
							'name' => __('Subsection item'), 
							'url' => ...,
							'permissions' => ...,
							'priority' => ...
						),
						array( // Subsubsection
							....
						)
					)
				)
			)
		)
	</pre>


	CREATE TABLE IF NOT EXISTS `api_keys` (
		`id` varchar(50) NOT NULL,
		`description` text NOT NULL,
		`created_on` datetime NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `email_templates` (
		`id` int(18) NOT NULL AUTO_INCREMENT,
		`created_on` datetime DEFAULT NULL,
		`email_type` int(5) unsigned NOT NULL,
		`status` int(1) NOT NULL DEFAULT '1',
		`email_from` varchar(255) NOT NULL DEFAULT '',
		`email_to` varchar(255) NOT NULL DEFAULT '',
		`subject` varchar(255) DEFAULT NULL,
		`message` text,
		`message_type` varchar(5) NOT NULL DEFAULT 'html',
		`bcc` text,
		`reply_to` varchar(255) DEFAULT NULL,
		`cc` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`),
		KEY `email_template_type` (`email_type`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `email_types` (
		`id` int(18) unsigned NOT NULL AUTO_INCREMENT,
		`code` varchar(255) NOT NULL DEFAULT '',
		`name` varchar(100) DEFAULT NULL,
		`data` text,
		PRIMARY KEY (`id`),
		UNIQUE KEY `email_type_Code` (`code`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

	ALTER TABLE `__TABLE_PREFIX__email_templates`
	  ADD CONSTRAINT `email_templates_ibfk_1` FOREIGN KEY (`email_type`) REFERENCES `__TABLE_PREFIX__email_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

### 9.4.13

	* ALTER TABLE  `page_widgets` ADD  `position` INT( 4 ) NOT NULL DEFAULT  '500';

	* ALTER TABLE  `pages` ADD  `password` VARCHAR( 50 ) NOT NULL DEFAULT  '';

### 9.15.46

	* $page->content(..) -> Part::content($page, ...)
	* $page->has_content(...) -> Part::exists($page, ...)
	* $page->field(...) -> Page_Field::get($page, ...)
	* $page->has_field($page, ...) -> Page_Field::exists($page, ...)


### 12.20.37

Организация папок в разделе Datasource
После обновления необходимо выполнить SQL скрипт, если вы используете преффикс для таблиц, его необходимо учесть.

	CREATE TABLE IF NOT EXISTS `datasource_folders` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(64) NOT NULL DEFAULT '',
	  `position` int(11) DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	ALTER TABLE `datasources` ADD `folder_id` int(11) NOT NULL;