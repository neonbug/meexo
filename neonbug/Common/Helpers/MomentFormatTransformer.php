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
		return str_replace(
			array_keys($this->moment_date_format_tokens_to_php), 
			array_values($this->moment_date_format_tokens_to_php), 
			$momentPattern
		);
	}
	
}
