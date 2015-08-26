<?php namespace Neonbug\Common\Helpers;

class FormatterHelper {
	
	private $moment_format_transformer;
	private $moment_locales;
	private $current_locale;
	
	public function __construct($locale, $momentFormatTransformer)
	{
		$this->moment_locales = include(__DIR__ . '/locale/locale.php');
		$this->current_locale = $this->findLocale($locale);
		$this->moment_format_transformer = $momentFormatTransformer;
	}
	
	protected function findLocale($locale)
	{
		return \Locale::lookup(array_keys($this->moment_locales), $locale, false, 'en-US');
	}
	
	public function getCurrentLocale()
	{
		return $this->current_locale;
	}
	
	public function getShortDatePattern($locale = null)
	{
		if ($locale == null) $locale = $this->current_locale;
		return $this->moment_locales[$locale]['L'];
	}
	public function getPhpShortDatePattern($locale = null)
	{
		return $this->moment_format_transformer->transformToPhp($this->getShortDatePattern($locale));
	}
	public function formatShortDate($timestamp, $locale = null)
	{
		return date($this->getPhpShortDatePattern($locale), $timestamp);
	}
	
	public function parseLocalesFromMomentJsLocaleDir($path)
	{
		//output of this can be used for locale.php by echoing it using var_export
		
		$arr = scandir($path);
		$files = [];
		foreach ($arr as $item)
		{
			if ($item == '.' || $item == '..') continue;
			if (mb_strlen($item) < 3 || mb_substr($item, -3) != '.js') continue;
			
			$files[] = $item;
		}
		
		$locales = [
			'en-US' => [
				'LT'   => 'h:mm A',
				'LTS'  => 'h:mm:ss A',
				'L'    => 'MM/DD/YYYY',
				'LL'   => 'MMMM D, YYYY',
				'LLL'  => 'MMMM D, YYYY h:mm A',
				'LLLL' => 'dddd, MMMM D, YYYY h:mm A'
			]
		];
		
		foreach ($files as $file)
		{
			$output_array = [];
			preg_match_all('/.*longDateFormat.*\{(.*)\}.*/imsU', file_get_contents($path . '/' . $file), $output_array);
			
			$format_strings = [];
			preg_match_all('/(.*):.*\'(.*)\'.*/iU', $output_array[1][0], $format_strings);
			if (sizeof($format_strings) != 3 || sizeof($format_strings[1]) != sizeof($format_strings[2])) continue;
			
			$arr = [];
			for ($i=0; $i<sizeof($format_strings[1]); $i++)
			{
				$arr[trim($format_strings[1][$i])] = trim($format_strings[2][$i]);
			}
			
			$locales[mb_substr($file, 0, mb_strlen($file)-3)] = $arr;
		}
		
		return $locales;
	}
	
}
