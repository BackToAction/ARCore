<?php

namespace ARCore\Pets;

class BlockPet extends Pets {

	const NETWORK_ID = 66;

	public $width = 1;
	public $height = 2;

	public function getName() {
		return "BlockPet";
	}

	public function getSpeed() {
		return 0.5;
	}

}
