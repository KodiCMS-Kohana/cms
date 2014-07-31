# KodiCMS

[Official Site](http://www.kodicms.com/) | [Youtube](http://www.youtube.com/channel/UCgZ25N9C1F8uoTXZZK55mqQ) | [Forum](http://www.kodicms.ru/forum.html)

KodiCMS is a CMS based on [Kohana framework](http://kohanaframework.org/). 
> Kohana - An elegant HMVC PHP5 framework that provides a rich set of components for building web applications. 
> You can create your own modules, plugins in full using the tools of the framework.


## Features

* based on [Kohana framework](http://kohanaframework.org/)
* Backend UI based on [Twitter Bootstrap 2.3](http://twitter.github.com/bootstrap/)
* Enhanced with plugins
* Modularity
* Use `Observer` to extend the basic functionality
* Unlimited level pages
* High speed
* Обработка ошибочных URL. (Если посетитель допустил ошибку URL, скорее всего он не получит в ответ: Страница не найдена)
* Widgets
* Syntax Highlighter [Ace](http://ace.c9.io/)
* Role-based access control (ACL)
* Integration with social networks
* Email templates and email events
* Cron jobs
* Easy installer
* API
* Ease of development


## Demo
[http://demo.kodicms.ru/](http://demo.kodicms.ru/)

> **Admin:** [http://demo.kodicms.ru/backend](http://demo.kodicms.ru/backend)

> Login: **demo** / Password: **demodemo**


## Forum

[http://www.kodicms.com/forum#/categories/english](http://www.kodicms.com/forum#/categories/english)

## Requirements

* Apache server with .htaccess or NGINX
* PHP 5.3.3+
* MySQL

## Install

1. Clone Github repository [https://github.com/butschster/kodicms.git](https://github.com/butschster/kodicms.git) or download the latest version of the [download zip file](https://github.com/butschster/kodicms/zipball/master)

2. Place the files on your web-server.

> When you install the site is not in the root directory, you must edit the files:
> * `.htaccess => RewriteBase /subfolder/`
> * `cms\app\bootstrap.php` => `Kohana::init( array( 'base_url' => '/subfolder/', ... ) );`

3. Open the home page in browser and begin the installation process.

> When an error occurs ErrorException [ 2 ]: date() [function.date]: It is not 
> safe to rely on the system's timezone settings. You are required to use the 
> date.timezone setting or the date_default_timezone_set() function.
> ....
> In file `cms/app/bootstrap.php` is a string `date_default_timezone_set( 'UTC' )`, 
> you must uncomment it.
> [Available time zones](http://www.php.net/manual/timezones)

5. Fill in all required fields and click install
6. After installation, you will be taken to the login page where you will see your username and password to login.


### Sample configuration for Nginx

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
	
		# Блокируем доступ для всех скрытых файлов,
		# таких как .htaccess, .git, .svn и т.д.
		location ~ /\.ht {
			deny all;
		}
	}


### Example file . htaccess for Apache

	# Set environment
	SetEnv KOHANA_ENV production
	# SetEnv KOHANA_ENV development
	SetEnv KOHANA_BASE /
	SetEnv BASE_URL http://www.example.com
	
	# Turn on URL rewriting
	RewriteEngine On
	
	# Installation directory
	RewriteBase /
	
	# Protect hidden files from being viewed
	<Files .*>
		Order Deny,Allow
		Deny From All
	</Files>
	
	# Protect application and system files from being viewed
	RewriteRule ^(?:cms|layouts|public|snippets)\b.* index.php/$0 [L]
	
	# Allow any files or directories that exist to be displayed directly
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	
	# Rewrite all other URLs to index.php/URL
	RewriteRule .* index.php/$0 [PT]# Set environment


## Bug tracker

If you have any problems while using KodiCMS, inform them on our bug tracker .
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