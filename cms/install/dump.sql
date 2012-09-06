SET FOREIGN_KEY_CHECKS=0;

INSERT INTO `TABLE_PREFIX_pages` (`id`, `title`, `slug`, `breadcrumb`, `keywords`, `description`, `parent_id`, `layout_file`, `behavior_id`, `status_id`, `created_on`, `published_on`, `updated_on`, `created_by_id`, `updated_by_id`, `position`, `needs_login`) VALUES
(1, 'Home', '', 'Home', '', '', 0, 'normal', '', 100, '__DATE__', '__DATE__', '__DATE__', 1, 1, 0, 0);

INSERT INTO `TABLE_PREFIX_roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'administrator', 'Administrative user, has access to everything.'),
(3, 'developer', 'Developers role'),
(4, 'editor', '');

INSERT INTO `TABLE_PREFIX_roles_users` (`user_id`, `role_id`) VALUES
(1, 1),
(1, 2);

INSERT INTO `TABLE_PREFIX_settings` (`name`, `value`) VALUES
('admin_title', '__SITE_NAME__'),
('language', '__LANG__'),
('default_status_id', '100'),
('default_filter_id', ''),
('default_tab', '/admin/page'),
('profiling', 'no');

INSERT INTO `TABLE_PREFIX_users` (`id`, `email`, `username`, `name`, `password`, `logins`, `last_login`) VALUES
(1, '__EMAIL__', '__USERNAME__', 'Administrator', '__ADMIN_PASSWORD__', 0, 0);

SET FOREIGN_KEY_CHECKS=1;