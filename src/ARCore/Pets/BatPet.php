<?php

namespace ARCore\Pets;

class BatPet extends Pets {

	const NETWORK_ID = 19;

	public $width = 0.6;
	public $length = 0.6;
	public $height = 0.6;


	public $flySpeed = 0.8;
	public $switchDirectionTicks = 100;

	public function getName() {
		return "BatPet";
	}

	public function getSpeed() {
		return 0.8;
	}

}
