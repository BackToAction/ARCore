<?php

namespace ARCore\Pets;

class CowPet extends Pets{

const NETWORK_ID = 11;
	public $width = 0.3;
	public $length = 0.9;
	public $height = 1.8;

  public function getName(){
    return "CowPet";
  }
    public function getSpeed(){
      return 1.5;
    }
  
}
