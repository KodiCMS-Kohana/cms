<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Text extends Kohana_Text 
{
	public static function translit($string)
	{
		$rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
		$lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');

		return str_replace($rus, $lat, $string);
	}

	/**
	 * 
	 * @param string $haystack
	 * @param string $needle
	 * @return boolean
	 */
	public static function starts_with($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	/**
	 * 
	 * @param string $haystack
	 * @param string $needle
	 * @return boolean
	 */
	public static function ends_with($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0)
		{
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}

	/**
	 * 
	 * @param string $word
	 * @param array $words
	 * @return array
	 */
	public static function similar_word($word, array $words)
	{
		$config = Kohana::$config->load('similar');
		$similarity = $config->similarity;
		$meta_similarity = 0;
		$min_levenshtein = 1000;
		$meta_min_levenshtein = 1000;

		$result = array();
		$meta_result = array();

		foreach ($words as $n)
		{
			$min_levenshtein = min($min_levenshtein, levenshtein($n, $word));
		}

//		foreach($words as $n)
//		{
//			if(levenshtein($n, $word) == $min_levenshtein)
//			{
//				$similarity = max($similarity, similar_text($n, $word));
//			}
//		}

		foreach ($words as $n => $k)
		{
			if (levenshtein($k, $word) <= $min_levenshtein)
			{
				if (similar_text($k, $word) >= $similarity)
				{
					$result[$n] = $k;
				}
			}
		}

		foreach ($result as $n)
		{
			$meta_min_levenshtein = min($meta_min_levenshtein, levenshtein(metaphone($n), metaphone($word)));
		}

		foreach ($result as $n)
		{
			if (levenshtein($n, $word) == $meta_min_levenshtein)
			{
				$meta_similarity = max($meta_similarity, similar_text(metaphone($n), metaphone($word)));
			}
		}


		foreach ($result as $n => $k)
		{
			if (levenshtein(metaphone($k), metaphone($word)) <= $meta_min_levenshtein)
			{
				if (similar_text(metaphone($k), metaphone($word)) >= $meta_similarity)
				{
					$meta_result[$n] = $k;
				}
			}
		}

		return $meta_result;
	}
}