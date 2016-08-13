<?php

namespace pets;

class OcelotPet extends Pets {

	const NETWORK_ID = 22;
	
	public $width = 0.312;
	public $length = 2.188;
	public $height = 0.75;
	
		
	public function getName() {
		return "OcelotPet";
	}
		
	public function getSpeed() {
		return 2.0;
	}
}
