<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use App\Models\DB\User;

class Graduation extends Model
{
	protected $fillable = ['user_id','course','institution','conclusion_at','order'];
	protected $dates = ['conclusion_at', 'created_at', 'updated_at'];

	public function user(){
		return $this->belongsTo(User::class);
	}
}