<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

return array(

	/*********************** Template *********************************/
	'Access denied' => 'Доступ запрещен',
	'Error' => 'Ошибка',
	'Errors' => 'Ошибки',
	'Success' => 'Выполнено',
	
	'Hide menu' => 'Скрыть меню',

	'Copy' => 'Копировать',
	'Date' => 'Дата',
	'Design' => 'Дизайн',
	'Cancel' => 'Отмена',
	'Description' => 'Описание',
	'Direction' => 'Расположение',
	'Hidden' => 'Скрыта',
	'Information' => 'Информация',
	'Actions' => 'Действия',
	'Search' => 'Поиск',
	'Home' => 'Главная',
	'Login' => 'Вход',
	'Logout' => 'Выйти',
	'Not required' => 'Не обязательно',
	'Required' => 'Обязательно',
	'Pending' => 'Ожидает',
	'Remove' => 'Удалить',
	'Save and Close' => 'Сохранить и Закрыть',
	'Save' => 'Сохранить',
	'Save and Continue editing' => 'Сохранить и Продолжить',
	'Save settings' => 'Сохранить настройки',
	'Save changes' => 'Сохранить изменения',
	'System' => 'Система',
	'inherit' => 'наследовать',
	'n/a' => 'н/д',
	'none' => 'отcутствует',
	'optional' => 'не обязательно',
	'or' => 'или',
	'unknown' => 'неизвестно',
	'Loading' => 'Загрузка',
	'View Site' => 'Просмотреть сайт',
	'View' => 'Просмотреть',
	'Are you sure?' => 'Вы уверены?',
	'Something went wrong!' => 'Что то пошло не так',
	'Select all' => 'Выбрать все',
	'Next' => 'Далее',
	'Previous' => 'Назад',
	'Key' => 'Ключ',
	'Never' => 'Никогда',
	'Or' => 'Или',
	'List' => 'Список',
	'Download' => 'Скачать',
	'Failed to validate array' => 'Ошибка',
	'KodiCMS successfully installed!' => 'KodiCMS успешно установлена',
	'--- none ---' => '--- не указан ---',
	'Thank you for using :site' => 'Спасибо, за использование :site',
	'Powered by :framework v:version :codename' => 'Работает на :framework v:version :codename',
	'Items per page' => 'Кол-во элементов на странице',
	'Admin Theme :name' => 'Тема :name',
	'The requested view :file could not be found' 
		=> 'Запрашиваемый файл шаблона :file не найден',
	'File is not writable' => 'Файл только для чтения',
	'Folder :folder is not writable' => 'Директория :folder только для чтения',
	
	/*********************** Layouts *********************************/
	'Add layout' => 'Добавить шаблон',
	'Edit layout' => 'Редактировать шаблон',
	'View layout' => 'Просматривать шаблон',
	'Layout' => 'Шаблон',
	'Layout has been saved!' => 'Шаблон сохранен!',
	'Layout has been deleted!' => 'Шаблон удален!',

	'Layout is used! It CAN NOT be deleted!' 
		=> 'Шаблон используется! Он не может быть удален!',

	'Layout not found!' => 'Шаблон не найден!',
	'Layout name' => 'Название шаблона',
	'Layouts' => 'Шаблоны',
	'Modified' => 'Изменен',
	'Layout blocks' => 'Блоки',
	'Rebuild blocks' => 'Обновить список блоков',
	'Layout blocks successfully update!' => 'Список блоков успешно обновлен!',
	'Read only' => 'Только чтение',
	'Size' => 'Размер',
	'Rebuild block list' => 'Обновлять список блоков',

	/*********************** Settings *******************/
	'General settings' => 'Главные настройки',
	'Interface language' => 'Язык интерфейса',
	'Default interface language' => 'Язык интерфейса по умолчанию',
	'Site information' => 'Информация о сайте',
	'Site title' => 'Заголовок',
	'Site description' => 'Описание',
	'Regional settings' => 'Региональные настройки',
	'Setting'  => 'Настройка',
	'Settings' => 'Настройки',
	'This status will be auto selected when page creating.' 
		=> 'Этот статус будет выбран по-умолчанию при создании страницы.',
	'Date format' => 'Формат даты',
	'Debug mode' => 'Режим отладки',
	'Login page' => 'Страница для входа',
	'Profiling' => 'Профайлинг',
	'Show breadcrumbs' => 'Показывать хлебные крошки',
	'Yes' => 'Да',
	'No' => 'Нет',
	'Enabled' => 'Включен',
	'Disabled' => 'Выключен',
	'Debug' => 'Разработка',
	'Find similar pages' => 'Похожая страница',
	'This text will be present at backend and can be used in frontend pages.' 
		=> 'Этот текст будет использоваться в Backend, а также использоваться во Frontend',
	'If requested page url is incorrect, then find similar page.' 
		=> 'Если запрашиваемая страница не найдена, то показать наиболее похожую страницу',
	'This allows you to specify which section you will see by default after login.' 
		=> 'Это позволит сразу перейти к нужному разделу после входа.',
	'Default backend section' => 'Раздел по-умолчанию панели управления',
	'Default HTML editor' => 'Редактор текста по умолчанию',
	'Default Code editor' => 'Редактор кода по умолчанию',
	'Default page status' => 'Статус страницы по-умолчанию',
	'Settings has been saved!' => 'Настройки сохранены!',
	'Site settings' => 'Настройки сайта',
	'Page settings' => 'Настройки страницы',
	'Check page date' => 'Проверять дату создания страницы',
	'For detailed profiling use Kohana::$enviroment = Kohana::DEVELOPMENT or SetEnv KOHANA_ENV DEVELOPMENT in .htaccess' 
		=> 'Для детального профилирования используйте Kohana::$enviroment = Kohana::DEVELOPMENT или SetEnv KOHANA_ENV DEVELOPMENT в файле .htaccess',
	'Revision templates' => 'Ревизия шаблонов',
	'After save layouts or snippets create revision copy in logs directory'
		=> 'После сохранения сниппетов или шаблонов создавать делать ревизию в директории logs',
	'Only for filter in pages, <i>not</i> in snippets.' => 'Только для фильтров в страницах, не в сниппетах',
	'CMS name' => 'Название CMS',
	'CMS Version' => 'Версия CMS',
	'PHP Version' => 'Версия PHP',
	'Kohana version' => 'Версия Kohana',
	'Kohana enviroment' => 'Текущее окружение',
	'Web server' => 'Web сервер',
	'MySQL driver' => 'MySQL драйвер',
	'PHP info' => 'Информация о PHP',
	'Session storage' => 'Хранение сессии',
	'Session settings' => 'Настройки сессии',
	'Clear user sessions' => 'Сбросить сессии пользователей',
	'User sessions has been cleared!' => 'Пользовательские сессии сброшены',
	'The session storage driver can change in the config file (:path)' 
		=> 'Драйвер хранения сессии можно изменить через конфиг файл (:path)',
	'Server host' => 'Хост',
	'Check URL suffix' => 'Проверять наличие URL суффикса',
	'(Sec.)' => '(Сек.)',
	'KodiCMS API key' => 'Ключ KodiCMS API',
	'Ace settings' => 'Ace',
	'Select theme' => 'Выберите тему',
	
	/*********************** Autorization *******************/
	'Username' => 'Логин',
	'Username or email' => 'Логин или E-mail',
	'Remember me for :num days' => 'Запомнить меня на :num дней',
	'Forgot password?' => 'Забыли пароль?',
	'Forgot password' => 'Вспомнить пароль',
	'Login failed. Please check your login data and try again.' 
		=> 'Не удалось войти. Пожалуйста, проверьте логин и пароль, и попробуйте еще раз.',
	'Needs login' => 'Необходим вход',
	'No user found!' => 'Пользователь не найден!',
	'Use a valid e-mail address.' 
		=> 'Используйте правильный адрес эл. почты.',
	'Enter your e-mail, which you want to forgot password.' => 
		'Укажите email адрес, для которого вы хотите восстановить пароль.',
	'Send password' => 'Отправить пароль',
	'Login: :login' => 'Логин: :login',
	'Password: :password' => 'Пароль: :password',
    
	'Message has been sent from' => 'Сообщение отправлено с сайта',
	'Your new password from :site_name' 
		=> 'Ваш новый пароль от сайта :site_name',

    'Sorry, an error has occurred, Requested page not found!' 
		=> 'Извините, произошла ошибка. Запрошенной страницы не существует!',
	
	/*********************** Languages *******************/
	'Russian' => 'Русский',

	/*********************** Date *******************/
    'am' => 'дп',
    'pm' => 'пп',
    'AM' => 'ДП',
    'PM' => 'ПП',
    'Monday' => 'Понедельник',
    'Mon' => 'Пн',
    'Tuesday' => 'Вторник',
    'Tue' => 'Вт',
    'Wednesday' => 'Среда',
    'Wed' => 'Ср',
    'Thursday' => 'Четверг',
    'Thu' => 'Чт',
    'Friday' => 'Пятница',
    'Fri' => 'Пт',
    'Saturday' => 'Суббота',
    'Sat' => 'Сб',
    'Sunday' => 'Воскресенье',
    'Sun' => 'Вс',
    'January' => 'Января',
    'Jan' => 'Янв',
    'February' => 'Февраля',
    'Feb' => 'Фев',
    'March' => 'Марта',
    'Mar' => 'Мар',
    'April' => 'Апреля',
    'Apr' => 'Апр',
    'May' => 'Мая',
    'May' => 'Мая',
    'June' => 'Июня',
    'Jun' => 'Июн',
    'July' => 'Июля',
    'Jul' => 'Июл',
    'August' => 'Августа',
    'Aug' => 'Авг',
    'September' => 'Сентября',
    'Sep' => 'Сен',
    'October' => 'Октября',
    'Oct' => 'Окт',
    'November' => 'Ноября',
    'Nov' => 'Ноя',
    'December' => 'Декабря',
    'Dec' => 'Дек',
    'st' => 'ое',
    'nd' => 'ое',
    'rd' => 'е',
    'th' => 'ое',
	'Hour' => 'Час',
	'Minute' => 'Минута',
	'Month' => 'Месяц',
	'Year' => 'Год',
	
	//============Permissions============//
	'Permissions' => 'Права',
	'Allow' => 'Разрешить',
	'Deny' => 'Запретить',
	'View layouts' => 'Видеть раздел',
	'Delete layout' => 'Удаление шаблонов',
	'View pages' => 'Видеть раздел',
	'Add pages' => 'Добавлять новые страницы',
	'Edit pages' => 'Редактировать страницы',
	'Sort pages' => 'Сортировать',
	'Set page permissions' => 'Устанавливать права доступа',
	'Manage custom fields' => 'Управлять дополнительными полями',
	'Manage parts' => 'Управлять частями страниц',
	'Delete pages' => 'Удалять страницы',
	'Active' => 'Активен',
	'Inactive' => 'Неактивен',
	'Manage social accounts' => 'Управлять аккаунтами соц. сетей',
	'View settings' => 'Видеть раздел настроек',
	'View system information' => 'Видеть информацию о системе',
	'View PHP info' => 'Видеть вкладку PHP info',
    
    //============Dropzone============//
    'Drop files here to upload' => 'Перетащите сюда файлы для загрузки на сервер',
    "Your browser does not support drag'n'drop file uploads." 
        => "Ваш браузер не поддерживает drag'n'drop загрузку",
    "File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB."
        => 'Размер файла ({{filesize}}MB) слишком большой. Максимальный размер {{maxFilesize}}MB.',
    "You can't upload files of this type." => 'Вы не можете загружать файлы этого типа.',
    "Server responded with {{statusCode}} code." => 'Сервер ответил с кодом {{statusCode}}.',
    "Cancel upload" => 'Отмена загрузки',
    "Are you sure you want to cancel this upload?" => 'Вы уверены, что хотите отменить загрузку?',
    "Remove file" => 'Удалить файл',
    "You can only upload {{maxFiles}} files." => 'Вы можете загрузить только {{maxFiles}} файла(-ов).',

	// Validation rules
	'Not empty' => 'Обязательно',
	'Phone number' => 'Номер телефона',
	'Email domain' => 'Email домен',
	'Credit card' => 'Кредитная карта',
	'Alpha' => 'Буквы',
	'Alpha and hyphens' => 'Буквы и дефис',
	'Alpha and numbers' => 'Буквы и числа',
	'Integer digit' => 'Целое число',
	'Decimal' => 'Десятичное число',
	'Numeric' => 'Число',
	'Color' => 'Цвет',
	
	/* User Validation Messages */
	'User :value is not found.' => 'Пользователь :value не найден.',
	'Invalid username or password.' => 'Неверное имя пользователя или пароль.',
	
	/* System Messagae Validation Translation */
	// 'alpha'
	':field must contain only letters' => 'Поле ":field" может состоять только из букв латинского алфавита',
	// 'alpha_dash'
	':field must contain only numbers, letters and dashes' => 'Поле ":field" может состоять только из цифр, букв латинского алфавита и знака тире',
	// 'alpha_numeric'
	':field must contain only letters and numbers' => 'Поле ":field" может состоять только из цифр и букв латинского алфавита',
	// 'color'
	':field must be a color' => 'Поле ":field" должно быть цветом',
	// 'credit_card'
	':field must be a credit card number' => 'Поле ":field" должно быть номером кредитной карты',
	// 'date'
	':field must be a date' => 'Поле ":field" должно быть датой',
	// 'decimal'
	':field must be a decimal with :param2 places' => 'Поле ":field" должно быть десятичным числом с :param2 знаками после запятой',
	// 'digit'
	':field must be a digit' => 'Поле ":field" должно быть целым числом',
	// 'email'
	':field must be an email address' => 'Поле ":field" должно быть корректным email адресом',
	// 'email_domain'
	':field must contain a valid email domain' => 'Поле ":field" должно содержать корректный домен электронной почты',
	// 'equals'
	':field must equal :param2' => 'Поле ":field" должно быть равно :param2',
	// 'exact_length'
	':field must be exactly :param2 characters long' => 'Длина поля ":field" должна быть равной :param2 символа(ов)',
	// 'in_array'
	':field must be one of the available options' => 'Поле ":field" может содержать один из доступных вариантов',
	// 'ip'
	':field must be an ip address' => 'Поле ":field" должно быть правильным IP адресом',
	// 'matches'
	':field must be the same as :param3' => 'Поле ":field" должно совпадать с полем ":param3"',
	// 'min_length'
	':field must be at least :param2 characters long' => 'Поле ":field" должно быть не менее :param2 символа(ов)',
	// 'max_length'
	':field must not exceed :param2 characters long' => 'Поле ":field" должно быть не более :param2 символа(ов)',
	// 'not_empty'
	':field must not be empty' => 'Поле ":field" обязательно к заполнению',
	// 'numeric'
	':field must be numeric' => 'Поле ":field" должно быть числом',
	// 'phone'
	':field must be a phone number' => 'Поле ":field" должно быть телефонным номером',
	// 'range'
	':field must be within the range of :param2 to :param3' => 'Поле ":field" должно быть в промежутке от :param2 до :param3',
	// 'regex'
	':field does not match the required format' => 'Недопустимый формат поля ":field"',
	// 'url'
	':field must be a url' => 'Поле ":field" должно быть корректным адресом web сайта',
	// 'incorrect'
	':field is invalid'		=> 'Неверное значение в поле ":field"',
	'Field :field must be unique' => 'Поле :field должно быть уникальным',
	'Incorrect token' => 'Не верный токен',
);