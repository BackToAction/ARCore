<?php

namespace ARCore\Pets;

class ChickenPet extends Pets {

	const NETWORK_ID = 10;

	public $width = 0.4;
	public $height = 0.75;
	
	public function getName() {
		return "ChickenPet";
	}

	public function getSpeed() {
		return 1;
	}
	

}
