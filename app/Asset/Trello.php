<?php 

namespace App\Asset;

use Config;

class Trello extends AssetBundle {
	
	protected $priority = 3;
	
	public function __construct(){
		$appKey = Config::get('trello.key');
        $this->baseJsPath = '';
        $this->jsfiles  = ['https://trello.com/1/client.js?key='.$appKey];
	}
	
}