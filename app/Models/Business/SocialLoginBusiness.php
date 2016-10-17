<?php

namespace App\Models\Business;

use App\Models\DB\User;
use App\Models\Enums\EnumUser;
use App\Models\Enums\EnumSocialLogin;
use App\Utils\DateUtil;

class SocialLoginBusiness 
{
	public function findUserByIdAndProvider($id, $provider)
	{
		return User::where('social_driver', '=', $provider)
				   ->where('social_id', '=', $id)
				   ->first();
	}

	public function parseFBData($fbUser)
	{
		$userData = $fbUser->user;
		$user = [
			"name" 			=> $userData['name'],
			"email" 		=> isset($userData['email']) ? $userData['email'] : null,
			"skills" 		=> "",
			"gender" 		=> $userData['gender'] == 'male' ? EnumUser::MALE : EnumUser::FEMALE,
			"birth_date" 	=> null,
			"photo"			=> $fbUser->getAvatar(),
			"social_id" 	=> $userData['id'],
			"social_driver" => EnumSocialLogin::FACEBOOK
		];
		$user['works'] 		 = $this->parseFBWork($fbUser);
		$user['graduations'] = $this->parseFBEducation($fbUser);
		return $user;
	}

	public function parseFBWork($userData)
	{
		$works = [];
		if (isset($userData['work']) && !empty($userData['work'])) {
			$required = ['employer', 'position', 'start_date'];
			foreach ($userData['work'] as $arrWork) {
				$keys = array_keys($arrWork);
				if (count(array_intersect($keys, $required)) == count($required)) {
					array_push($works,[
						'title' 	  => $arrWork['position']['name'],
						'company' 	  => $arrWork['employer']['name'],
						'description' => null,		
						'started_at'  => DateUtil::strDbDateToBrDate($arrWork['start_date'], false),
						'ended_at' 	  => isset($arrWork['end_date']) ? DateUtil::strDbDateToBrDate($arrWork['end_date'], false) : null
					]);
				}
			}
		}
		return $works;
	}

	public function parseFBEducation($userData)
	{
		$graduations = [];
		if (isset($userData['education']) && !empty($userData['education'])) {
			$required = ["school","type","year", "concentration"];
			foreach ($userData['education'] as $arrEducation) {
				$keys = array_keys($arrEducation);
				if (count(array_intersect($keys, $required)) == count($required)) {
					array_push($graduations,[
						'course' 		=> $arrEducation['concentration'][0]['name'],
						'institution' 	=> $arrEducation['school']['name'],
						'conclusion_at' => '01/12/'.$arrEducation['year']['name']
					]);
				}	
			}
		}
		return $graduations;
	}

	public function parseGPData($gpUser)
	{
		if (isset($gpUser->user['gender'])) {
			$gender = ($gpUser->user['gender'] == 'male') ? EnumUser::MALE : EnumUser::FEMALE;
		} else {
			$gender = null;
		}

		$user = [
			"name" 			=> $gpUser->getName(),
			"email" 		=> $gpUser->getEmail(),
			"skills" 		=> "",
			"gender" 		=> $gender,
			"birth_date" 	=> null,
			"photo"			=> $gpUser->getAvatar(),
			"social_id" 	=> $gpUser->getId(),
			"social_driver" => EnumSocialLogin::GOOGLE_PLUS
		];	 

		return $user;
	}	
}