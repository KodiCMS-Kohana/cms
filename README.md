# KodiCMS | [English Version](https://github.com/butschster/kodicms/blob/dev/README_EN.md)

[Официальный сайт](http://www.kodicms.ru/) | [Канал на Youtube](http://www.youtube.com/channel/UCgZ25N9C1F8uoTXZZK55mqQ) | [Форум](http://www.kodicms.ru/forum.html)

KodiCMS основана на базе [Kohana framework](http://kohanaframework.org/). 
> Kohana - фреймворк для создания web приложений. Вы можете создавать собственные модули, 
> плагины в полном объеме используя инструменты фреймворка.


## Ключевые особенности

* Ядро на базе [Kohana framework](http://kohanaframework.org/)
* Backend UI на базе [Twitter Bootstrap 3.2.0](http://getbootstrap.com/) и темы [PixelAdmin](https://wrapbootstrap.com/theme/pixeladmin-premium-admin-theme-WB07403R9)
* Расширение при помощи плагинов
* Модульность
* Использование `Observer` для расширения базового функционала
* Неограниченный уровень страниц
* Высокая скорость работы
* Обработка ошибочных URL. (Если посетитель допустил ошибку URL, скорее всего он не получит в ответ: Страница не найдена)
* Виджеты
* Файловый менеджер [elFinder](https://github.com/Studio-42/elFinder)
* Визуальный редактор [Ace](http://ace.c9.io/)
* Разграничение прав для пользователей (ACL)
* Интеграция с соц. сетями
* Почтовые шаблоны и события для почовых уведомлений
* Запуск задач по расписанию
* Удобный инсталлятор
* API
* Простота разработки
* Возможность выбрать место хранения кеша (file, sqlite, apc, memcache, mongodb)
* Возможность выбора места хранения сессии (native, cookie, database)


## Демо сайт
[http://demo.kodicms.ru/](http://demo.kodicms.ru/)

> **Admin:** [http://demo.kodicms.ru/backend](http://demo.kodicms.ru/backend)

> Login: **demo** / Password: **demodemo**


## Форум

[http://www.kodicms.ru/forum.html](http://www.kodicms.ru/forum.html)

## Требования

* Apache server with .htaccess либо NGINX
* PHP 5.3.3 (или более новая)
* MySQL (и доступ к управлению данными)


## Установка

1. Создайте клон репозитория `https://github.com/butschster/kodicms.git` или 
[скачайте zip архив](https://github.com/butschster/kodicms/zipball/master)
с последней версией.

2. Разместите файлы на вашем web-сервере.

	> При установке сайта не в корневую директорию, необходимо в двух местах внести изменения.
	> В файлах:
	> * `.htaccess => RewriteBase /subfolder/`
	> * `cms\app\bootstrap.php` => `Kohana::init( array( 'base_url' => '/subfolder/', ... ) );`

3. Перед установкой необходимо удалить, либо очистить содержимое файла config.php, если он имеется в корне сайта.
	Также необходимо установить права на запись и чтение для следующих папок:
	* `cms/logs`
	* `cms/cache`
	* `cms/tmp`
	* `layouts`
	* `snippets`
	* `public`

	Через консоль можно сделать с помощью команды `chmod -R a+rwx ...`, например `chmod -R a+rwx cms/cache`

4. Откройте главную страницу через браузер. Запустится процесс интсалляции системы.

	> **Если возникла ошибка ErrorException [ 2 ]: date() [function.date]: It is not 
	> safe to rely on the system's timezone settings. You are required to use the 
	> date.timezone setting or the date_default_timezone_set() function.**
	> ....<br />
	> В `cms/app/bootstrap.php` есть строка `date_default_timezone_set( 'UTC' )`, 
	> необходимо ее разкомментировать.
	> [Доступные временные зоны](http://www.php.net/manual/timezones)

	>  **Если возникла ошибка Call to a member function load() on a non-object in cms/application/classes/config.php on line 16**<br />
	>  Необходимо выполнить пункт 3.

	>  **Если возникла ошибка Fatal error: Undefined class constant Log::EMERGENCY in /cms/system/classes/kohana/kohana/exception.php on line 140**<br />
	>  Версия PHP ниже 5.3

5. Заполните все необходимые поля и нажмите кнопку "Установить". 
6. После установки системы вы окажетесь на странице авторизации, где будет указан ваш логин и пароль для входа в систему.


## Установка через Cli (Консоль)

> KodiCMS можно установить через консоль.
> Для установки используется модуль `Minion`

1. Перед установкой необходимо удалить файл **config.php**, если он имеется в корне сайта

2. Перейти в корень папки **kodicms**

3. выполнить команду `php index.php --task=install`. 

> Полный набор параметров можно посмотреть через **help** `php index.php --task=install --help`


### Пример конфигурации для Nginx

	server{
		listen 127.0.0.1:80;
		server_name   example.com www.example.com;
		
		# PublicRoot нашего сайта
		root          /srv/http/example.com/public_html;
		index         index.php;
		
		# Устанавливаем пути к логам
		# Для access_log делаем буферизацию
		access_log    /srv/http/example.com/logs/access.log main buffer=50k;
		error_log     /srv/http/example.com/logs/error.log;
		
		charset       utf8;
		autoindex     off;
	
		location / {
			if (!-f $request_filename) {
				rewrite ^/(.*)$ /index.php;
			}
		}

		# Подключаем обработчик php-fpm
		location ~ \.php$ {
		
			# Этой строкой мы указываем,
			# что текущий location можно использовать
			# только для внутренних запросов
			# Тем самым запрещаем обработку всех php файлов,
			# для которых не создан location
			internal;
			
			# php-fpm. Подключение через сокет.
			fastcgi_pass   unix:/var/run/php-fpm/php-fpm.sock;
			# или fastcgi_pass   127.0.0.1:9000;
			fastcgi_param   KOHANA_ENV development;
			# или fastcgi_param   KOHANA_ENV production;
			fastcgi_index  index.php;
			fastcgi_param  DOCUMENT_ROOT  /srv/http/oskmedia/public_html;
			fastcgi_param  SCRIPT_FILENAME  /srv/http/oskmedia/public_html$fastcgi_script_name;
			include fastcgi_params;
		}
	
		# Блокируем доступ извне, к файлам и папкам:
			# таким как .htaccess
			location ~ /\.ht {
				deny all;
				return 404;
			}

			# а также каталогов .git, .svn
			location ~.(git|svn) {
	        	deny  all;
	            return 404;
	        }


	}


## Баг трекер

Если у вас возникли проблемы во время использования CMS, сообщайте их на баг трекер.
[https://github.com/butschster/kodicms/issues](https://github.com/butschster/kodicms/issues)


## Copyright and license

KodiCMS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

KodiCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with KodiCMS.  If not, see <http://www.gnu.org/licenses/>.

KodiCMS has made an exception to the GNU General Public License for plugins.
See exception.txt for details and the full text.


> Copyright 2014 Buchnev Pavel <butschster@gmail.com>.
