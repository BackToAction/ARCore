<?php

namespace ARCore\Pets;

class EndermanPet extends Pets{

 	const NETWORK_ID = 38;
	public $width = 0.3;
	public $length = 0.9;
	public $height = 1.8;

  Public function getName(){
      return "EndermanPet";
}
  
  Public function getSpeed(){
  return 1.0;
   }
}
