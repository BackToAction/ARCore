<?php

namespace ARCore\Pets;

class BatPet extends Pets {

	const NETWORK_ID = 19;

	public $width = 0.6;
	public $height = 0.6;

	public $switchDirectionTicks = 100;

	public function getName() {
		return "BatPet";
	}

	public function getSpeed() {
		return 0.8;
	}

}
