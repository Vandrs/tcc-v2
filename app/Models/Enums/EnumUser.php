<?php

namespace App\Models\Enums;

class EnumUser 
{
	const MALE 	 = 'M';
	const FEMALE = 'F';

	public static function getGenderLabels()
	{
		return [
			self::MALE 	 => 'Masculino',
			self::FEMALE => 'Feminino'
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