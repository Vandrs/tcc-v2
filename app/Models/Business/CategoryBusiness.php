<?php

namespace App\Models\Business;
use App\Models\DB\Category;

class CategoryBusiness{

	public static function getCategoriesForDropDown(){
		$categories = Category::orderBy('name','ASC');
		$comboCategories = [];
		$categories->each(function($category) use(&$comboCategories){
			$comboCategories[$category->id] = $category->name;
		});
		return $comboCategories;
	}
}