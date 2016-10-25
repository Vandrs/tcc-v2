<?php 

namespace App\Models\Enums;

class EnumLikert 
{
	CONST MUITO_SATISFEITO = 5;
	CONST SATISFEITO = 4;
	CONST SEM_OPINAR = 3;
	CONST INSATISFEITO = 2;
	CONST MUITO_INSATISFEITO = 1;

	public static function getLabels() 
	{
		return [
			self::MUITO_SATISFEITO => "Muito satisfeito",
			self::SATISFEITO => "Satisfeito",
			self::SEM_OPINAR => "Não sei opinar",
			self::INSATISFEITO => "Insatisfeito ",
			self::MUITO_INSATISFEITO => "Muito Insatisfeito",
		];
	}	

	public static function getLabel($id) 
	{
		$labels = self::getLabels();
		if (isset($labels[$id])) {
			return $labels[$id];
		}
		return null;

	}
}


