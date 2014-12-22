<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Block {

	/**
	 * Проверка блока на наличие в нем виджетов
	 * 
	 * @param type string|array
	 * @return boolean
	 */
	public static function is_empty($name)
	{
		if (!is_array($name))
		{
			$name = array($name);
		}

		$blocks = Context::instance()->get_blocks();

		foreach ($name as $block)
		{
			if (in_array($block, $blocks))
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * Метод служит для разметки выводимых блоков на странице
	 * 
	 * @param string $name
	 * @param array $params
	 */
	public static function run($name, array $params = array())
	{
		if ($name == 'PRE' OR $name == 'POST')
		{
			return;
		}

		$widgets = self::get($name, $params);

		foreach ($widgets as $widget)
		{
			if ($widget instanceof View)
			{
				echo $widget
					->render();
			}
			else if ($widget instanceof Model_Widget_Decorator)
			{
				$widget
					->render();
			}
			else if ($widget instanceof Model_Widget_Part)
			{
				echo $widget;
			}
		}
	}

	/**
	 * Получение виджетов блока без вывода.
	 * 
	 * Для вывода данных блока
	 * 
	 *		$widget = Block::get('block_name', $params);
	 *		if(is_array($widget))
	 *		{
	 *			foreach($widget as $data)
	 *			{
	 *				echo $data;
	 *			}
	 *		}
	 *		else
	 *			echo $widget;
	 * 
	 * @param string $name
	 * @param array $params Дополнительные параметры доступные в виджете
	 * @return array
	 */
	public static function get($name, array $params = array())
	{
		$ctx = Context::instance();

		$widgets = $ctx->get_widgets_by_block($name);
		
		foreach ($widgets as $widget)
		{
			if ($widget instanceof View)
			{
				$widget
					->bind('ctx', $ctx)
					->set('params', $params);
			}
			else if ($widget instanceof Model_Widget_Decorator)
			{
				$widget->set_params($params);
			}
		}

		return $widgets;
	}

	/**
	 * Блок типа def служит для помещения в него виджетов без вывода.
	 * Необходим в том случае, если необходимо на странице вывести виджет 
	 * внутри другого виджета, но без разметки блока в шаблоне, в него не получится
	 * поместить виджет.
	 * 
	 * Т.е. в основном шаблоне в самом низу мы указываем, например:
	 * 
	 *		Block::def('block_name_def');
	 * 
	 * Теперь в него можно поместить виджет, далее в шаблоне виджета, в котором
	 * мы хотим его вывести пишем:
	 * 
	 *		Block::run('block_name_def');
	 * 
	 * @param string $name
	 */
	public static function def($name)
	{
		
	}

	/**
	 * Метод служит для поиска в переданном шаблоне размеченных блоков
	 * 
	 * @param string $content
	 * @return string
	 */
	public static function parse_content($content)
	{
		$content = str_replace(' ', '', $content);
		preg_match_all("/Block::([a-z_]{3,5})\(\'([0-9a-zA-Z\_\-\.]+)\'(\,.*)?\)/i", $content, $blocks);

		if (!empty($blocks[2]))
		{
			return $blocks[2];
		}

		return NULL;
	}

}