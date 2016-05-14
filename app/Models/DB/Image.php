<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use App\Utils\ImageFileServerTrait;

class Image extends Model{
	use ImageFileServerTrait;
	protected $fillable = ['project_id','title','file','cover'];
}