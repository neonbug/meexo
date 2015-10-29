<?php namespace Neonbug\Common\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Neonbug\Common\Traits\PasswordTraitInterface as PasswordTraitInterface;

class User extends BaseModel implements AuthenticatableContract, PasswordTraitInterface {

	use Authenticatable;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'username', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];
	
	public function roles()
	{
		return $this->belongsToMany('\Neonbug\Common\Models\Role', 'user_role', 'id_user', 'id_role');
	}
	
	public static function getPasswordFields() { return ['password']; }

}
