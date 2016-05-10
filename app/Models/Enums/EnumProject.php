<?php

namespace App\Models\Enums;

class EnumProject{

	CONST STATUS_ACTIVE = 1;
	CONST STATUS_INACTIVE = 0;
	CONST ROLE_OWNER = 1;
	CONST ROLE_MENTOR = 2;
	CONST ROLE_CONTRIBUTOR = 3;
	CONST ROLE_FOLLOWER = 4;

	private static function getRoleName($role){
		$roles = self::getRoles();
		if(isset($roles[$role])){
			return $roles[$role];
		}
		return '';
	}

	private static function getRoles(){
		return [
			self::ROLE_OWNER  	   => 'Colaborador',
			self::ROLE_MENTOR 	   => 'Mentor',
			self::ROLE_CONTRIBUTOR => 'Colaborador',
			self::ROLE_FOLLOWER    => 'Seguidor'
		];
	}
}