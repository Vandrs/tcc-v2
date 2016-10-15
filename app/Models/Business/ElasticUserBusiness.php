<?php

namespace App\Models\Business;
use App\Models\DB\User;

class ElasticUserBusiness
{

	private static $instance;

	public static function instance()
	{
		if (empty(self::$instance)) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public static function getElasticUserData(User $user)
	{
		$attributes = self::instance()->getBaseAttributes($user);
		$attributes['graduations'] = self::instance()->getGraduations($user);
		$attributes['works'] = self::instance()->getWorks($user);
		return $attributes;
	}

	private function getBaseAttributes(User $user)
	{
		$excludeItems = ['password','remember_token', 'trello_token', 'social_id', 'social_driver',];
		$attributes = $user->getAttributes();
		foreach ($excludeItems as $item) {
			if (isset($attributes[$item])) {
				unset($attributes[$item]);
			}
		}
		return $attributes;
	}

	private function getGraduations(User $user)
	{
		if ($user->graduations->count()) {
			return $user->graduations->toArray();
		}
		return [];
	}

	private function getWorks(User $user)
	{
		if ($user->works->count()) {
			return $user->works->toArray();
		}
		return [];
	}

}