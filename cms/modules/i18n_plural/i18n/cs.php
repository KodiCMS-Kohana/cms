<?php defined('SYSPATH') or die('No direct script access.');

return array(
	// Plural test
	':count files'	=> array(
		'one' => ':count soubor',
		'few' => ':count soubory',
		'other' => ':count souborů',
	),
	// Date/time
	'date' => array(
		'months' => array('Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'),
		'months_abbr' => array('ledna', 'února', 'března', 'dubna', 'května', 'června', 'července', 'srpna', 'září', 'října', 'listopadu', 'prosince'),
		'days' => array('Neděle', 'Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota'),
		'days_abbr' => array('ne', 'po', 'út', 'st', 'čt', 'pá', 'so'),
		'date_order' => array('date', 'month', 'year'),
		'short_date' => '%d.%m.%Y',
		'short_time' => '%H:%M',
		'am' => 'dop.',
		'pm' => 'odp.',
		'less_than_minute_ago' => 'před chvílí',
		'minute_ago' => array(
			'one'	=> 'přibližně před minutou',
			'other' => 'před {delta} minutami', // same as 'few'
		),
		'hour_ago' => array(
			'one'	=> 'přibližně před hodinou',
			'other' => 'před {delta} hodinami',
		),
		'day_ago' => array(
			'one'	=> 'včera',
			'other' => 'před {delta} dny',
		),
		'week_ago' => array(
			'one'	=> 'před týdnem',
			'other' => 'před {delta} týdny',
		),
		'month_ago' => array(
			'one'	=> 'před měsícem',
			'other' => 'před {delta} měsíci',
		),
		'year_ago' => array(
			'one'	=> 'před rokem',
			'other' => 'před {delta} lety',
		),
		'less_than_minute_until' => 'za chvíli',
		'minute_until' => array(
			'one'	=> 'přibližně za minutu',
			'few' => 'za {delta} minuty',
			'other' => 'za {delta} minut',
		),
		'hour_until' => array(
			'one'	=> 'přibližně za hodinu',
			'few'	=> 'za {delta} hodiny',
			'other' => 'za {delta} hodin',
		),
		'day_until' => array(
			'one'	=> 'zítra',
			'few'	=> 'za {delta} dny',
			'other'	=> 'za {delta} dnů',
		),
		'week_until' => array(
			'one'	=> 'za týden',
			'few'	=> 'za {delta} týdny',
			'other' => 'za {delta} týdnů',
		),
		'month_until' => array(
			'one'	=> 'za měsíc',
			'few'	=> 'za {delta} měsíce',
			'other' => 'za {delta} měsíců',
		),
		'year_until' => array(
			'one'	=> 'za rok',
			'few'	=> 'za {delta} roky',
			'other' => 'za {delta} let',
		),
		'never' => 'nikdy',
	),
	'valid' => array(
		'alpha'			=> 'Pole :field může obsahovat pouze písmena',
		'alpha_dash'	=> 'Pole :field může obsahovat pouze písmena, číslice, pomlčku a potržítko',
		'alpha_numeric'	=> 'Pole :field může obsahovat pouze písmena a číslice',
		'color'			=> 'Do pole :field musíte zadat kód barvy',
		'credit_card'	=> 'Do pole :field musíte zadat platné číslo platební karty',
		'date'			=> 'Do pole :field musíte zadat datum',
		'decimal' => array(
			'one'		=> 'Do pole :field musíte zadat číslo s jedním desetinným místem',
			'other'		=> 'Do pole :field musíte zadat číslo s :param2 desetinnými místy',
		),
		'digit'			=> 'Do pole :field musíte zadat celé číslo',
		'email'			=> 'Do pole :field musíte zadat emailovou adresu',
		'email_domain'	=> 'Do pole :field musíte zadat platnou emailovou doménu',
		'equals'		=> 'Pole :field se musí rovnat :param2',
		'exact_length' => array(
			'one'		=> 'Pole :field musí být dlouhé přesně 1 znak',
			'few'		=> 'Pole :field musí být přesně :param2 znaky dlouhé',
			'other'		=> 'Pole :field musí být přesně :param2 znaků dlouhé',
		),
		'in_array'		=> 'Do pole :field musíte vložit pouze jednu z dovolených možností',
		'ip'			=> 'Do pole :field musíte zadat platnou ip adresu',
		'match'			=> 'Pole :field se musí shodovat s polem :param2',
		'max_length' => array(
			'few'		=> 'Pole :field musí být nanejvýš :param2 znaky dlouhé',
			'other'		=> 'Pole :field musí být nanejvýš :param2 znaků dlouhé',
		),
		'min_length' => array(
			'one'		=> 'Pole :field musí být alespoň jeden znak dlouhé',
			'few'		=> 'Pole :field musí být alespoň :param2 znaky dlouhé',
			'other'		=> 'Pole :field musí být alespoň :param2 znaků dlouhé',
		),
		'not_empty'		=> 'Pole :field nesmí být prázdné',
		'numeric'       => ':field musí mít číselnou hodnotu',
		'phone'			=> 'Pole :field musí být platné telefonní číslo',
		'range'			=> 'Hodnota pole :field musí ležet v intervalu od :param2 do :param3',
		'regex'			=> 'Pole :field musí splňovat požadovaný formát',
		'url'			=> 'Do pole :field musíte zadat platnou adresu URL',
	),
);