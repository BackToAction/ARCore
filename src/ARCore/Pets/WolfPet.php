<?php

namespace ARCore\Pets;

class WolfPet extends Pets {

	const NETWORK_ID = 14;

	public $width = 0.72;
	public $height = 0.9;

	public function getName() {
		return "WolfPet";
	}

	public function getSpeed() {
		return 1.2;
	}

}
