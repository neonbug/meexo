<?php namespace Neonbug\Common\Helpers;

class MomentFormatTransformer {
	
	private $moment_date_format_tokens_to_php = [
		'MMMM'  => 'F',    'MMM'   => 'M',    'MM'    => 'm',     'Mo'    => '', 
		'M'     => 'n',    'Q'     => '',     'DDDD'  => '',      'DDDo'  => '', 
		'DDD'   => 'z',    'DD'    => 'd',    'Do'    => 'jS',    'D'     => 'j', 
		'dddd'  => 'l',    'ddd'   => 'D',    'dd'    => '',      'do'    => '', 
		'd'     => 'w',    'e'     => 'w',    'E'     => 'N',     'ww'    => '', 
		'wo'    => '', 	   'w'     => 'W',    'WW'    => '',      'Wo'    => '', 
		'W'     => 'W',    'YYYY'  => 'Y',    'YY'    => 'y',     'gggg'  => 'Y', 
		'gg'    => 'y',    'GGGG'  => 'Y',    'GG'    => 'y',     'A'     => 'A', 
		'a'     => 'a',    'HH'    => 'H',    'H'     => 'G',     'hh'    => 'h', 
		'h'     => 'g',    'mm'    => 'i',    'm'     => 'i',     'ss'    => 's', 
		's'     => 's',    'SSSSS' => 'u',    'SSSS'  => 'u',     'SSS'   => 'u', 
		'SS'    => 'u',    'S'     => 'u',    'zz'    => 'e',     'z'     => 'e', 
		'ZZ'    => 'O',    'Z'     => 'P',    'X'     => 'U',     'x'     => 'U000', 
	];
	
	public function __construct()
	{
	}
	
	public function transformToPhp($momentPattern)
	{
		$matching_string = "%%%|*|%%%";
		foreach ($this->moment_date_format_tokens_to_php as $key => $value) {
			$offset = 0;
			do { 
				$str = strpos($momentPattern, $key, $offset);
				if ($str === false) {
					break;
				} 
				if (substr($momentPattern, $str, strlen($key) + strlen($matching_string)) == $key.$matching_string) {
					$offset = $str + strlen($key);
					continue;
				} else {
					$momentPattern = substr_replace($momentPattern, $value.$matching_string, $str, strlen($key));
					$offset = $str + strlen($key);
				}		
			} while (true);		
		}
		return str_replace($matching_string, "", $momentPattern);
	}
}
