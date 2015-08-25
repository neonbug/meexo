<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('translation_source', function(Blueprint $table)
		{
			$table->string('id_translation_source', 255);
			$table->timestamps();
			
			$table->primary('id_translation_source');
		});
		
		Schema::create('translation', function(Blueprint $table)
		{
			$table->increments('id_translation');
			$table->string('id_translation_source', 255);
			$table->integer('id_language');
			$table->text('value');
			$table->timestamps();
			
			$table->foreign('id_translation_source')->references('id_translation_source')->on('translation_source');
			$table->foreign('id_language')->references('id_language')->on('language');
			
			$table->index('id_translation_source');
			$table->index('id_language');
		});
		
		$translations = [];
		$translations_dir = __DIR__ . '/translations.common/';
		if (file_exists($translations_dir))
		{
			$arr = scandir($translations_dir);
			foreach ($arr as $item)
			{
				if ($item == '.' || $item == '..') continue;
				if (mb_strlen($item) < 4 || mb_substr($item, -4) != '.php') continue;
				
				$translations = array_merge($translations, include($translations_dir . $item));
			}
		}
		
		$languages = [];
		$translation_keys = [];
		foreach ($translations as $key=>$values)
		{
			$translation_keys[] = $key;
			foreach ($values as $lang=>$value)
			{
				if (!in_array($lang, $languages)) $languages[] = $lang;
			}
		}
		
		if (sizeof($languages) > 0)
		{
			$locale_to_id_languages = [];
			$trans = DB::table('language')->whereIn('locale', $languages)->get();
			foreach ($trans as $item)
			{
				$locale_to_id_languages[$item->locale] = $item->id_language;
			}
			
			$translation_source_insert_arr = [];
			foreach ($translation_keys as $key)
			{
				$translation_source_insert_arr[] = [
					'id_translation_source' => $key, 
					'created_at'            => date('Y-m-d'), 
					'updated_at'            => date('Y-m-d')
				];
			}
			
			DB::table('translation_source')->insert($translation_source_insert_arr);
			
			$translation_insert_arr = [];
			foreach ($translations as $key=>$values)
			{
				foreach ($values as $lang=>$value)
				{
					if (!array_key_exists($lang, $locale_to_id_languages)) continue;
					$id_language = $locale_to_id_languages[$lang];
					
					$translation_insert_arr[] = [
						'id_translation_source' => $key, 
						'id_language'           => $id_language, 
						'value'                 => $value, 
						'created_at'            => date('Y-m-d'), 
						'updated_at'            => date('Y-m-d')
					];
				}
			}
			
			DB::table('translation')->insert($translation_insert_arr);
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('role');
	}

}
