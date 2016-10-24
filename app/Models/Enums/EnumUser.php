<?php

namespace App\Models\Enums;

class EnumUser 
{
	const MALE 	 = 'M';
	const FEMALE = 'F';

	public static function getGenderLabels()
	{
		return [
			self::FEMALE => 'Feminino',
			self::MALE 	 => 'Masculino'
		];
	}

	public function getGenderLabel($key)
	{
		$labels = self::getLabels();
		if (isset($labels[$key])) {
			return $labels[$key];
		}
		return null;
	}
}