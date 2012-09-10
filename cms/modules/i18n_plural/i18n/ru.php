<?php defined('SYSPATH') or die('No direct script access.');

return array(
	// Plural test
	':count files'	=> array(
		'one' => ':count файл',
		'few' => ':count файла',
		'many' => ':count файлов',
		'other' => ':count файла',
	),
	// Date/time
	'date' => array(
		'months' => array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'),
		'months_abbr' => array('янв', 'февр', 'март', 'апр', 'май', 'июнь', 'июль', 'авг', 'сент', 'окт', 'нояб', 'дек'),
		'days' => array('Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'),
		'days_abbr' => array('Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'),
		'date_order' => array('date', 'month', 'year'),
		'short_date' => '%d.%m.%Y',
		'short_time' => '%H:%M',
		'am' => 'AM',
		'pm' => 'PM',
		'less_than_minute_ago' => 'меньше минуты назад',
		'minute_ago' => array(
			'one'	=> 'минуту назад',
			'many'	=> '{delta} минут назад',
			'other'	=> '{delta} минуты назад', // same as 'few'
		),
		'hour_ago' => array(
			'one'	=> 'час назад',
			'many' => '{delta} часов назад',
			'other' => '{delta} часа назад',
		),
		'day_ago' => array(
			'one'	=> 'вчера',
			'many' => '{delta} дней назад',
			'other' => '{delta} дня назад',
		),
		'week_ago' => array(
			'one'	=> 'неделю назад',
			'many' => '{delta} недель назад',
			'other' => '{delta} недели назад',
		),
		'month_ago' => array(
			'one'	=> 'месяц назад',
			'many' => '{delta} месяцев назад',
			'other' => '{delta} месяца назад',
		),
		'year_ago' => array(
			'one'	=> 'год назад',
			'many' => '{delta} лет назад',
			'other' => '{delta} года назад',
		),
		'less_than_minute_until' => 'меньше чем через минуту',
		'minute_until' => array(
			'one'	=> 'через минуту',
			'many' => 'через {delta} минут',
			'other' => 'через {delta} минуты',
		),
		'hour_until' => array(
			'one'	=> 'через час',
			'many' => 'через {delta} часов',
			'other' => 'через {delta} часа',
		),
		'day_until' => array(
			'one'	=> 'через день',
			'many' => 'через {delta} дней',
			'other' => 'через {delta} дня',
		),
		'week_until' => array(
			'one'	=> 'через неделю',
			'many' => 'через {delta} недель',
			'other' => 'через {delta} недели',
		),
		'month_until' => array(
			'one'	=> 'через месяц',
			'many' => 'через {delta} месяцев',
			'other' => 'через {delta} месяца',
		),
		'year_until' => array(
			'one'	=> 'через год',
			'many' => 'через {delta} лет',
			'other' => 'через {delta} года',
		),
		'never' => 'никогда',
	),
	'valid' => array(
		'alpha'			=> 'Поле :field должно содержать только буквы',
		'alpha_dash'	=> 'Поле :field должно содержать только буквы, цифры, тире и знак подчеркивания',
		'alpha_numeric'	=> 'Поле :field должно содержать только буквы и цифры',
		'color'			=> 'Поле :field должно содержать цветовой код',
		'credit_card'	=> 'Поле :field должно содержать действительный номер платежной карточки',
		'date'			=> 'Поле :field должно содержать дату',
		'decimal' => array(
			'one'		=> 'Поле :field должно содержать число с :param2 десятичным местом',
			'other'		=> 'Поле :field должно содержать число с :param2 десятичными местами',
		),
		'digit'			=> 'Поле :field должно содержать целое число',
		'email'			=> 'Поле :field должно содержать адрес электронной почты',
		'email_domain'	=> 'Поле :field должно содержать действительный адрес электронной почты',
		'equals'		=> 'Значение поля :field должно быть равно :param2',
		'exact_length' => array(
			'one'		=> 'Поле :field должно быть длиной в :param2 знак',
			'few'		=> 'Поле :field должно быть длиной в :param2 знака',
			'other'		=> 'Поле :field должно быть длиной в :param2 знаков',
		),
		'in_array'		=> 'Поле :field должно содержать один из вариантов на выбор',
		'ip'			=> 'Поле :field должно содержать действительный ip адрес',
		'match'			=> 'Поле :field должно быть равно значению поля :param2',
		'max_length' => array(
			'one'		=> 'Поле :field должно иметь длину максимум :param2 знак',
			'few'		=> 'Поле :field должно иметь длину максимум :param2 знака',
			'other'		=> 'Поле :field должно иметь длину максимум :param2 знаков',
		),
		'min_length' => array(
			'one'		=> 'Поле :field должно иметь длину хотя бы :param1 знак',
			'few'		=> 'Поле :field должно иметь длину хотя бы :param1 знака',
			'other'		=> 'Поле :field должно иметь длину хотя бы :param1 знаков',
		),
		'not_empty'		=> 'Поле :field должно быть заполнено',
		'numeric'       => 'Поле :field должно иметь численное значение',
		'phone'			=> 'Поле :field должно содержать действительный номер телефона',
		'range'			=> 'Величина поля :field должна быть в интервале между :param2 и :param3',
		'regex'			=> 'Поле :field должно соответствовать заданному формату',
		'url'			=> 'Поле :field должно содержать действительный адрес URL',
	),
);