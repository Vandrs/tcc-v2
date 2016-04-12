<?php

namespace App\Models\DB;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\DB\ProjectNote;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function notes(){
        return $this->hasMany(ProjectNote::class);
    }
}
