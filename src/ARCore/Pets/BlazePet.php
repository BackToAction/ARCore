<?php

namespace ARCore\Pets;

class BlazePet extends Pets {

	const NETWORK_ID = 43;

	public $width = 0.4;
	public $height = 0.75;

	public function getName() {
		return "BlazePet";
	}

	public function getSpeed() {
		return 0.5;
	}

}
