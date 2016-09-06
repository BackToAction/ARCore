<?php

namespace ARCore\Pets;


class SheepPet extends Pets{
const NETWORK_ID = 13;

	public $width = 0.625;
	public $length = 1.4375;
	public $height = 1.8;
	
	public function getName(){
	  return "SheepPet";
	  }
	  
	  public function getSpeed(){
	  return 0.7;
	  }
	  
}
