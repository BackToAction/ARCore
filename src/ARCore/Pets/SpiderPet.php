<?php
 
 namespace ARCore\Pets;
 
 class SpiderPet extends Pets {
 
 	const NETWORK_ID = 35;
 	public $width = 0.3;
 	public $length = 0.9;
 	public $height = 1.9;
 	
 	public function getName() {
 		return "SpiderPet";
 	}
 	
 	public function getSpeed() {
 		return 2.0;
 	}
 
 }
