<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'similarity' => 3, // Степень схожести слова (Чем меньше число, тем меньше точность)
	'return_parent_page' => FALSE, // Включить переход на уровень выше, если слово не найдено
	'find_in_statuses' => array( // Статусы страниц, в которых искать
		Model_Page::STATUS_PASSWORD_PROTECTED, Model_Page::STATUS_PUBLISHED
	)
);
