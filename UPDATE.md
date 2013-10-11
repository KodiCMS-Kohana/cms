### 6.0.0

 * ALTER TABLE  `pages` ADD  `meta_title` VARCHAR( 255 ) NOT NULL DEFAULT  '' AFTER  `breadcrumb`;
 * ALTER TABLE  `pages` CHANGE  `keywords`  `meta_keywords` VARCHAR( 255 ) NOT NULL DEFAULT  '';
 * ALTER TABLE  `pages` CHANGE  `description`  `meta_description` TEXT;

### 6.4.20

 * Метод Model_Widget_Decorator::load_template_data изменен на backend_data. Необходимо переименовать в своих виджетах.
 * Если используются виджеты, наследуемые от Model_Widget_Decorator_Pagination, в backend шаблоне больше не нужны поля `list_offset` и `list_size`

### 6.5.21

 * ALTER TABLE  `pages` ADD  `robots` VARCHAR( 100 ) NOT NULL DEFAULT  'INDEX,FOLLOW' AFTER  `meta_description`;