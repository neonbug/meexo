<?php namespace Neonbug\Common\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey;
	
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = true;
	
	/**
	 * The database schema for the model.
	 *
	 * @var string
	 */
	protected $schema;

	/**
	 * Get the table associated with the model.
	 *
	 * @return string
	 */
	public function getTable()
	{
		$schema_prefix = '';
		$schema = $this->getSchema();
		if (isset($schema) && $schema != '') $schema_prefix = $schema . '.';
		
		if (isset($this->table)) 	$table = $this->table;
		else 						$table = str_replace('\\', '', snake_case(class_basename($this)));
		
		if (stripos($table, '.') !== false)
			return $table;
		else
			return $schema_prefix . $table;
	}
	
	public static function getTableName()
	{
		return with(new static)->getTable();
	}

	/**
	 * Get the schema associated with the model.
	 *
	 * @return string
	 */
	public function getSchema()
	{
		return $this->schema;
	}
	
	/**
	 * Get the primary key for the model.
	 *
	 * @return string
	 */
	public function getKeyName()
	{
		if (isset($this->primaryKey)) return $this->primaryKey;
		
		return 'id_' . str_replace('\\', '', snake_case(class_basename($this)));
	}
	
	public function getSlug()
	{
		//TODO write this method; it should take 'slug' from resources, transform it if necessary, and return it
	}
}
