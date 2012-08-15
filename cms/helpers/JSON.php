<?php if (!defined('CMS_ROOT')) die;

// Function json_encode from: http://ua.php.net/manual/en/function.json-encode.php#74878
if (!function_exists('json_encode'))
{
	function json_encode_string($in_str)
	{
		mb_internal_encoding("UTF-8");
		$convmap = array(0x80, 0xFFFF, 0, 0xFFFF);
		$str = "";
		
		for($i=mb_strlen($in_str)-1; $i>=0; $i--)
		{
			$mb_char = mb_substr($in_str, $i, 1);
			
			if(mb_ereg("&#(\\d+);", mb_encode_numericentity($mb_char, $convmap, "UTF-8"), $match))
			{
				$str = sprintf("\\u%04x", $match[1]) . $str;
			}
			else
			{
				$str = $mb_char . $str;
			}
		}
		return $str;
	}

	function json_encode($arr)
	{
		$json_str = "";
		
		if(is_array($arr))
		{
			$pure_array = true;
			$array_length = count($arr);
			for($i=0;$i<$array_length;$i++)
			{
				if(! isset($arr[$i]))
				{
					$pure_array = false;
					break;
				}
			}
			
			if($pure_array)
			{
				$json_str ="[";
				$temp = array();
				
				for($i=0;$i<$array_length;$i++)        
				{
					$temp[] = sprintf("%s", json_encode($arr[$i]));
				}
				
				$json_str .= implode(",",$temp);
				$json_str .="]";
			}
			else
			{
				$json_str ="{";
				$temp = array();
				
				foreach($arr as $key => $value)
				{
					$temp[] = sprintf("\"%s\":%s", $key, json_encode($value));
				}
				
				$json_str .= implode(",",$temp);
				$json_str .="}";
			}
		}
		else
		{
			if(is_string($arr))
			{
				$json_str = "\"". json_encode_string($arr) . "\"";
			}
			else if(is_numeric($arr))
			{
				$json_str = $arr;
			}
			else
			{
				$json_str = "\"". json_encode_string($arr) . "\"";
			}
		}
		
		return $json_str;
	}
}


// Function json_decode from http://ua.php.net/manual/en/function.json-decode.php#100740
if ( !function_exists('json_decode') )
{
	function json_decode($json)
	{
		$comment = false;
		$out = '$x=';

		for ($i=0; $i<strlen($json); $i++)
		{
			if (!$comment)
			{
				if (($json[$i] == '{') || ($json[$i] == '['))
					$out .= ' array(';
				else if (($json[$i] == '}') || ($json[$i] == ']'))
					$out .= ')';
				else if ($json[$i] == ':')
					$out .= '=>';
				else
					$out .= $json[$i];
			}
			else
				$out .= $json[$i];
				
			if ($json[$i] == '"' && $json[($i-1)]!="\\")
				$comment = !$comment;
		}
		
		eval($out . ';');
		return $x;
	}
}