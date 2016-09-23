<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use App\Models\DB\User;

class Work extends Model 
{
	protected $fillable = ['user_id', 'title', 'company', 'description', 'order', 'started_at', 'ended_at'];
	protected $dates = ['started_at', 'ended_at', 'created_at', 'updated_at'];

	public function user(){
		return $this->belongsTo(User::class);
	}
}