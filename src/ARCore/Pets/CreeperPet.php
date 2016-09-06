<?php

namespace ARCore\Pets;

class CreeperPet extends Pets {

    const NETWORK_ID = 33;
    
    public function getName(){
       return "CreeperPet";
       }
       
    public function getSpeed(){
      return 0.5;
      }
      
  }
