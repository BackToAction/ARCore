<?php

namespace ARCore\Pets;

class PigPet extends Pets {

	const NETWORK_ID = 12;

    public $width = 1.45;
    public $height = 1.12;


	public function getName() {
		return "PigPet";
	}

	public function getSpeed() {
		return 1.1;
	}

}
