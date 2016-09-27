<?php 

namespace App\Models\Elastic\Models;

use App\Models\Elastic\Models\ElasticModel;
use Illuminate\Support\Collection;
use App\Utils\DateUtil;

class ElasticUser extends ElasticModel {

	private $type = 'user';
	private $mappingFile = 'user.json';

	protected $fillable = ['id','name','email','skills','created_at','graduations','works','created_at'];

	public function __construct($attributes = null){
		parent::__construct($attributes, $this->type, $this->mappingFile);
	}

	protected function transform() {
		$createdAt = $this->created_at;
		if(!empty($createdAt) && !is_object($createdAt)){
			$this->created_at = DateUtil::dateTimeInBrazil($createdAt);
		}
		$updatedAt = $this->updated_at;
		if(!empty($updatedAt) && !is_object($updatedAt)){
			$this->updated_at = DateUtil::dateTimeInBrazil($updatedAt);
		}

		$graduations = $this->graduations;
		$this->graduations = new Collection();
		foreach($graduations as $key => $graduation){
			if (isset($graduation['conclusion_at']) && !empty($graduation['conclusion_at'])){
				$graduation['conclusion_at'] = DateUtil::dateTimeInBrazil($graduation['conclusion_at']);
			}
			$this->graduations->put($key, (object) $graduation);
		}

		$works = $this->works;
		$this->works = new Collection();
		foreach($works as $key => $work){
			if (isset($work['started_at']) && !empty($work['started_at'])){
				$work['started_at'] = DateUtil::dateTimeInBrazil($work['started_at']);
			}
			if (isset($work['ended_at']) && !empty($work['ended_at'])){
				$work['ended_at'] = DateUtil::dateTimeInBrazil($work['ended_at']);
			}
			$this->works->put($key, (object) $work);
		}

		if($this->skills){
			$this->skills = json_decode($this->skills);
		}
	}
}

