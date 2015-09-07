<?php namespace Neonbug\Common\Repositories;

use \Neonbug\Common\Models\Resource as Resource;

class ResourceRepository {
	
	var $cached_slugs = null;
	
	public function getSlugs($id_language, $table_name)
	{
		if ($this->cached_slugs == null)
		{
			$resources = Resource::where('id_language', $id_language)
				->where('column_name', 'slug')
				->get();
			$this->cached_slugs = array();
			foreach ($resources as $resource)
			{
				if (!array_key_exists($resource->table_name, $this->cached_slugs))
				{
					$this->cached_slugs[$resource->table_name] = array();
				}
				$this->cached_slugs[$resource->table_name][] = $resource;
			}
		}
		
		return (array_key_exists($table_name, $this->cached_slugs) ? $this->cached_slugs[$table_name] : array());
	}
	
	public function slugExists($table_name, $id_language, $value, $id_row = -1)
	{
		$query = Resource::where('id_language', $id_language)
			->where('column_name', 'slug')
			->where('value', $value)
			->where('table_name', $table_name);
		
		if ($id_row != -1)
		{
			$query = $query->where('id_row', '!=', $id_row);
		}
		
		return ($query->count() > 0);
	}
	
	/*
	$values should be in this format:
	[
		id_language1 => [
			'column_name1' => 'field value', 
			'column_name2' => 'field value'
		], 
		id_language2 => [
			...
		], 
		...
	]
	*/
	public function setValues($table_name, $id_row, Array $values)
	{
		$column_names = [];
		foreach ($values as $id_language=>$fields)
		{
			$column_names = array_merge($column_names, array_keys($fields));
		}
		$column_names = array_unique($column_names);
		
		$resources = Resource::where('table_name', $table_name)
			->where('id_row', $id_row)
			->whereIn('column_name', $column_names)
			->get();
		
		foreach ($values as $id_language=>$value_item)
		{
			foreach ($value_item as $column_name=>$value)
			{
				$found = false;
				foreach ($resources as $resource)
				{
					if ($column_name == $resource->column_name && $id_language == $resource->id_language)
					{
						$resource->value = $value;
						$resource->save();
						
						$found = true;
						break;
					}
				}
				
				if (!$found)
				{
					$resource = new Resource();
					$resource->id_language = $id_language;
					$resource->table_name  = $table_name;
					$resource->column_name = $column_name;
					$resource->value       = $value;
					$resource->id_row      = $id_row;
					$resource->save();
				}
			}
		}
	}
	
	//TODO maybe move these two functions somewhere else?
	public function inflateObjectWithValues($obj, $id_language)
	{
		$resources = Resource::where('table_name', $obj->getTableName())
			->where('id_row', $obj->{$obj->getKeyName()})
			->where('id_language', $id_language)
			->get();
		
		foreach ($resources as $resource)
		{
			$column_name = $resource->column_name;
			$value       = $resource->value;
			
			$obj->$column_name = $value;
		}
	}
	public function inflateObjectsWithValues($objects, $id_language)
	{
		$ids = [];
		$id_to_idx = [];
		
		$table_name = null;
		for ($i=0; $i<sizeof($objects); $i++)
		{
			if ($table_name == null) $table_name = $objects[$i]->getTableName();
			$id = $objects[$i]->{$objects[$i]->getKeyName()};
			
			$ids[]          = $id;
			$id_to_idx[$id] = $i;
		}
		
		if ($table_name == null) return;
		
		$resources = Resource::where('table_name', $table_name)
			->whereIn('id_row', $ids)
			->where('id_language', $id_language)
			->get();
		
		foreach ($resources as $resource)
		{
			$column_name = $resource->column_name;
			$value       = $resource->value;
			$id_row      = $resource->id_row;
			
			if (!array_key_exists($id_row, $id_to_idx)) continue;
			
			$objects[$id_to_idx[$id_row]]->$column_name = $value;
		}
	}
	
	/*
	returns values in this format:
	[
		id_language1 => [
			'column_name1' => 'field value', 
			'column_name2' => 'field value'
		], 
		id_language2 => [
			...
		], 
		...
	]
	 */
	public function getValues($table_name, $id_row)
	{
		$resources = Resource::where('table_name', $table_name)
			->where('id_row', $id_row)
			->get();
		
		$values = [];
		foreach ($resources as $resource)
		{
			if (!array_key_exists($resource->id_language, $values))
			{
				$values[$resource->id_language] = [];
			}
			$values[$resource->id_language][$resource->column_name] = $resource->value;
		}
		
		return $values;
	}
	
	public function deleteValues($table_name, Array $id_rows)
	{
		Resource::where('table_name', $table_name)
			->whereIn('id_row', $id_rows)
			->delete();
	}
	
}
