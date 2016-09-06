<?php

namespace ARCore\Pets;

class RabbitPet extends Pets {

	const NETWORK_ID = 18;
	
	const TYPE_WHITE = 1;

	public $width = 0.5;
	public $height = 0.5;
	
	public function getName() {
		return "RabbitPet";
	}

	public function getSpeed() {
		return 1.5;
	}
	

}
