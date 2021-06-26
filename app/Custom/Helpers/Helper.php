<?php

namespace App\Custom\Helpers;

use Illuminate\Support\Facades\DB;

class Helper
{
	/* Me: converts data to string */
	public static function str($mixed)
	{
		if (is_string($mixed) || is_int($mixed) || is_bool($mixed))
			return (string) $mixed;
		return '';
	}

	/* Me: returns escaped data */
	public static function safe($mixed)
	{
		if (is_array($mixed))
			foreach ($mixed as $key => $value)
				$mixed[$key] = self::safe($value);
		else if (is_string($mixed))
			return htmlspecialchars($mixed, ENT_QUOTES);

		return $mixed;
	}

	/* Me: returns substr of $str till the position of $numth occurance of $delimiter */
	public static function parse($str, $delimiter, $num)
	{
		$count = 0;
		$offset = -1;

		while ($count < $num) {
			if(($offset = strpos($str, $delimiter, $offset+1)) !== false)
				$count++;
			else
				return $str;
		}

		return substr($str, 0, $offset);
	}

	/* Me: checks if array contains only non-array elements */
	public static function isOneDimensionalArray($arr)
	{
		if (!is_array($arr))
			return false;

		return $arr === array_filter($arr, function ($var) {
            return !is_array($var);
        });
	}

	/* Me: names file */
	public static function makeFilePath($dirname, $basename, $file_exists_custom)
	{
	    $path = $dirname.'/'.$basename;
	    if (!call_user_func($file_exists_custom, $path))
	    	return $path;
	    $filename = pathinfo($basename, PATHINFO_FILENAME);
	    $ext = pathinfo($basename, PATHINFO_EXTENSION);

	    $i = 1;
	    while (call_user_func($file_exists_custom, $dirname.'/'.$filename.'-'.$i.'.'.$ext))
	    	$i++;
	    return $dirname.'/'.$filename.'-'.$i.'.'.$ext;
	}
}
