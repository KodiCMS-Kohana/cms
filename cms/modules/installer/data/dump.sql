SET FOREIGN_KEY_CHECKS=0;

INSERT INTO `__TABLE_PREFIX__pages` (`id`, `title`, `slug`, `breadcrumb`, `meta_title`, `meta_keywords`, `meta_description`, `parent_id`, `layout_file`, `behavior_id`, `status_id`, `created_on`, `published_on`, `updated_on`, `created_by_id`, `updated_by_id`, `position`, `needs_login`) VALUES
(1, 'Home', '', 'Home', 'Home', '', '', 0, 'normal', '', 100, '__DATE__', '__DATE__', '__DATE__', 1, 1, 0, 0),
(2, 'Page not found', 'page-not-found', 'Page not found', 'Page not found', '', '', 1, '', 'page_not_found', 101, '__DATE__', '__DATE__', '__DATE__', 1, 1, 4, 2),
(3, 'About us', 'about-us', 'About us', 'About us', '', '', 1, '', '', 100, '__DATE__', '__DATE__', '__DATE__', NULL, 1, 1, 2),
(4, 'RSS Feed', 'rss.xml', 'RSS Feed', 'RSS Feed', '', '', 1, 'none', '', 101, '__DATE__', '__DATE__', '__DATE__', 1, 1, 3, 2),
(5, 'My first article', 'my-first-article', 'My first article', 'My first article', '', '', 8, '', '', 100, '__DATE__', '__DATE__', NULL, NULL, NULL, 1, 2),
(6, '%B %Y archive', 'b-y-archive', '%B %Y archive', '%B %Y archive', '', '', 8, '', 'archive_month_index', 101, '__DATE__', '__DATE__', '__DATE__', 1, 1, 0, 2),
(7, 'My second article', 'my-second-article', 'My second article', 'My second article', '', '', 8, '', '', 100, '__DATE__', '__DATE__', NULL, NULL, NULL, 2, 2),
(8, 'Articles', 'articles', 'Articles', 'Articles', '', '', 1, '', 'archive', 100, '__DATE__', '__DATE__', '__DATE__', NULL, 1, 2, 2),
(9, 'Third entry', 'third-entry', 'Third entry', 'Third entry', '', '', 8, '0', '0', 100, '__DATE__', '__DATE__', '__DATE__', 1, 1, 3, 2),
(10, 'Fourth entry', 'fourth-entry', 'Fourth entry', 'Fourth entry', '', '', 8, '0', '0', 100, '__DATE__', '__DATE__', '__DATE__', 1, 1, 4, 2),
(11, 'Contacts', 'contacts', 'Контакты', 'Контакты', '', '', 1, '0', '0', 100, '__DATE__', '__DATE__', '__DATE__', 1, 1, 5, 2),
(12, 'Send page', 'send', 'send', 'send', '', '', 11, 'none', '0', 101, '__DATE__', '__DATE__', '__DATE__', 1, 1, 1, 2);


INSERT INTO `__TABLE_PREFIX__page_parts` (`id`, `name`, `filter_id`, `content`, `content_html`, `page_id`, `is_protected`) VALUES
(2, 'body', 'redactor', '<h3>Sorry</h3>\n\n<p>This page not found<br>\n</p>\n', '<h3>Sorry</h3>\n\n<p>This page not found<br>\n</p>\n', 2, 0),
(3, 'body', 'redactor', '<p>This is my site. I live in this city ... I do some nice things, like this and that ...</p>', '<p>This is my site. I live in this city ... I do some nice things, like this and that ...</p>', 3, 0),
(6, 'body', 'redactor', '<p>My first test of my first article.</p>\n', '<p>My first test of my first article.</p>\n', 5, 0),
(7, 'body', 'redactor', '<p>This is my second article.</p>\n', '<p>This is my second article.</p>\n', 7, 0);

INSERT INTO `__TABLE_PREFIX__page_roles` (`page_id`, `role_id`) VALUES
(1, 2),
(2, 2),
(3, 2),
(3, 3),
(3, 4),
(4, 2),
(4, 3),
(4, 4),
(5, 2),
(5, 3),
(5, 4),
(6, 2),
(6, 3),
(6, 4),
(7, 2),
(7, 3),
(7, 4),
(8, 2),
(8, 3),
(8, 4),
(11, 1),
(11, 2),
(11, 3),
(11, 4),
(12, 1),
(12, 2),
(12, 3),
(12, 4);

INSERT INTO `__TABLE_PREFIX__roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'administrator', 'Administrative user, has access to everything.'),
(3, 'developer', 'Developers role'),
(4, 'editor', '');

INSERT INTO `__TABLE_PREFIX__roles_users` (`user_id`, `role_id`) VALUES
(1, 1),
(1, 2);

INSERT INTO `__TABLE_PREFIX__users` (`id`, `email`, `username`, `password`, `logins`, `last_login`) VALUES
(1, '__EMAIL__', '__USERNAME__', '__ADMIN_PASSWORD__', 0, 0);

INSERT INTO `__TABLE_PREFIX__user_profiles` (`id`, `name`, `user_id`, `locale`, `created_on`) VALUES
(1, 'Administrator', 1, '__LANG__', '__DATE__');

INSERT INTO `__TABLE_PREFIX__email_templates` (`id`, `created_on`, `email_type`, `status`, `email_from`, `email_to`, `subject`, `message`, `message_type`, `bcc`, `reply_to`, `cc`) VALUES
(1, '2013-12-14 01:45:09', 1, 1, '{default_email}', '{email}', '{site_title}: Ссылка для восстановления пароля', '<h3>Здраствуйте {username}!</h3>Чтобы восстановить &nbsp;пароль от своего аккаунта, пройдите, пожалуйста, по ссылке:  <a href="{base_url}{reflink}">{base_url}{reflink}</a>&nbsp;или введите код&nbsp;<b>{code}</b> вручную на странице восстановления.<p>----------------------------------------</p><p>Данное письмо сгенерировано автоматически, отвечать на него не нужно.<span style="line-height: 1.45em;"></span></p>\n', 'html', NULL, NULL, NULL),
(2, '2013-12-14 15:00:31', 3, 1, '{email_from}', '{email}', '{site_title}: Новый пароль от вашего аккаунта', '<h3>Здраствуйте {username}!</h3>Ваш новый пароль:&nbsp;<b>{password}</b><p></p><p>Всегда храните свой пароль в тайне и&nbsp;не сообщайте его никому.<br></p><p>----------------------------------------</p><p><p>Данное письмо сгенерировано автоматически, отвечать на него не нужно.</p></p><p></p>', 'html', NULL, NULL, NULL);

INSERT INTO `__TABLE_PREFIX__email_types` (`id`, `code`, `name`, `data`) VALUES
(1, 'user_request_password', 'Запрос на восстановление пароля', 'a:4:{s:4:"code";s:48:"Код восстановления пароля";s:8:"username";s:31:"Имя пользователя";s:5:"email";s:30:"Email пользователя";s:7:"reflink";s:61:"Ссылка для восстановления пароля";}'),
(3, 'user_new_password', 'Новый пароль', 'a:3:{s:8:"password";s:23:"Новый пароль";s:5:"email";s:30:"Email пользователя";s:8:"username";s:31:"Имя пользователя";}');


SET FOREIGN_KEY_CHECKS=1;