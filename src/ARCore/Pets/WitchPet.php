<?php

namespace ARCore\Pets;

class WitchPet extends Pets{
  
  const NETWORK_ID = 45;
  
  public function getName(){
    return "WitchPet";
  }
  
  public function getSpeed(){
    return 0.7;
  }
  
}
