<?php 

namespace App\Models\Enums;

class EnumLikert 
{
	CONST MUITO_SATISFEITO = 5;
	CONST SATISFEITO = 4;
	CONST PARCIALMENTE_SATISFEITO = 3;
	CONST INSATISFEITO = 2;
	CONST SEM_OPINAR = 1;

	public static function getLabels() 
	{
		return [
			self::MUITO_SATISFEITO => "Muito satisfeito",
			self::SATISFEITO => "Satisfeito",
			self::PARCIALMENTE_SATISFEITO => "Parcialmente satisfeito",
			self::INSATISFEITO => "Insatisfeito ",
			self::SEM_OPINAR => "Não sei opinar",
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


