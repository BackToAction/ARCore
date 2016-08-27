<?php

namespace pets;

class SilverfishPet extends Pets {

	const NETWORK_ID = 39;

	public $width = 0.4;
	public $height = 0.75;
	
	public function getName() {
		return "SilverFish";
	}

	public function getSpeed() {
		return 1.5;
	}
	

}
