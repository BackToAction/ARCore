<?php

namespace ARCore\Pets;

class MagmaPet extends Pets {

	const NETWORK_ID = 42;

	public $width = 0.4;
	public $height = 0.75;

	public function getName() {
		return "MagmaPet";
	}

	public function getSpeed() {
		return 0.5;
	}

}
