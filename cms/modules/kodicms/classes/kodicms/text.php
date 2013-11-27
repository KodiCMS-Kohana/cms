<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		ButscHSter
 */
class KodiCMS_Text extends Kohana_Text 
{
	/**
	 * 
	 * @param string $haystack
	 * @param string $needle
	 * @return boolean
	 */
	public static function starts_with( $haystack, $needle )
	{
		$length = strlen( $needle );
		return (substr( $haystack, 0, $length ) === $needle);
	}

	/**
	 * 
	 * @param string $haystack
	 * @param string $needle
	 * @return boolean
	 */
	public static function ends_with( $haystack, $needle )
	{
		$length = strlen( $needle );
		if ( $length == 0 )
		{
			return true;
		}

		return (substr( $haystack, -$length ) === $needle);
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
		
		foreach($words as $n)
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

		foreach($words as $n => $k)
		{
			if(levenshtein($k, $word) <= $min_levenshtein)
			{
				if(similar_text($k, $word) >= $similarity)
				{
					$result[$n] = $k;
				}
			}
		}
		
		foreach($result as $n)
		{
			$meta_min_levenshtein = min($meta_min_levenshtein, levenshtein(metaphone($n), metaphone($word)));
		}
     
		foreach($result as $n)
		{
			if(levenshtein($n, $word) == $meta_min_levenshtein)
			{
				$meta_similarity = max($meta_similarity, similar_text(metaphone($n), metaphone($word)));
			}
		}
		
		
		foreach($result as $n => $k)
		{
			if(levenshtein(metaphone($k), metaphone($word)) <= $meta_min_levenshtein)
			{
				if(similar_text(metaphone($k), metaphone($word)) >= $meta_similarity)
				{
					$meta_result[$n] = $k;
				}
			}
		}

		return $meta_result;
	}

}

// End text
