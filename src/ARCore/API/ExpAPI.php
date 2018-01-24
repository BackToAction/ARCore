<?php

namespace ARCore\API;

use pocketmine\utils\Config;
use pocketmine\utils\Utils;
use ARCore\ARCore;
use ARCore\API\LevelAPI;
use ARCore\API\DatabaseAPI;

class ExpAPI {

    static public $instance;

    public function __construct(ARCore $plugin){
        $this->plugin = $plugin;
        $this->conf = new Config($this->getDataFolder() . "config.yml");
        $this->lvl = new LevelAPI::getInstance();
        $this->database = new DatabaseAPI::getInstance();
    }
    static public function getInstance(){
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getEXP($user){
        $i = $this->database->getPlayerDatabase($user);
        $result = $i->get("Exp");
        return $result;
    }
    public function getMaxEXP($user){
        $i = $this->database->getPlayerDatabase($user);
        $result = $i->get("MaxExp");
        return $result;
    }

    public function addEXP($user, $exp){
        $i = $this->database->getPlayerDatabase($user);
        $currentExp = $this->getEXP($user);
        $maxEXP = $this->getMaxEXP($user);
        if($currentExp >= 0){// rule no 1 // check to ensure it is not a negative
            if($maxEXP <= $currentExp + $exp){// rule no 2 // if the max exp is less than or same from the current exp plus the adding exp
                $cl = $currentExp - $maxEXP;
                $i->set("Exp", $cl);
                $i->save();
                $nw = $i->get("Exp");
                $mx = $i->get("MaxExp");
                $this->lvl->LevelUp($user);// Level Up !!
                if($nw >= $mx){
                    $this->checkerAdd($user);
                }
            }else{// rule no 3 // if the current exp plus the adding exp is less tha max Exp
                $i->set("Exp", $currentExp + $exp);
                $i->save();
            }
        }
    }

    public function checkerAdd($user){
        $i = $this->database->getPlayerDatabase($user);
        $cExp = $this->getEXP($user);
        $mE = $this->getMaxEXP($user);
        if($cExp > $mE){
            $this->addEXP($user, 0);
        }
    }

    public function addMaxEXP($user){
        $i = $this->database->getPlayerDatabase($user);
        $maxEXP = $this->getMaxEXP($user);
        $multipler = $this->conf->get("Level.MultipleExpPerLevel");
        $addExp = $this->conf->get("Level.AddMaxExp");
        $maxLevel = $this->conf->get("Level.Max%Level");
        $playerLvl = $this->lvl->getLevel($user);
        if($multipler <= 1){
            $i->set("Level.MultipleExpPerLevel", 2);
            $i->save();
            $defMulti = 2;
            if($playerLvl < $maxLevel){
                $c = $playerLvl; // I dont - 1 cause this was suppose to be level up setup.
                $calc = 100*($defMulti^($c)) + $addExp; // Tn = ar^n-1
                $i->set("MaxExp", $calc);
                $i->save();
            }
        }elseif($playerLvl < $maxLevel){
            $c = $maxEXP - 1;
            $calc = 100*($defMulti^($c)) + $addExp;
            $i->set("MaxExp", $calc);
            $i->save();
        }
    }

    public function reduceExp($user, $reduce){ // later on... damn it.
        $i = $this->database->getPlayerDatabase($user);
        $currentExp = $this->getExp($user);
        $currentMaxExp = $this->getMaxEXP($user);
        $currentLvL = $this->lvl->getLevel($user);
        // Tn = ar^n-1 //a = 100, r = def-multiplier; (n = Last Level [For This I Use Last Level Cause]) 
        if($reduce >= 0){
            $cal_one = $currentExp - $reduce;
            if($cal_one < 0){
                $cal_two = $reduce - $currentExp;
                $cal_four = $currentLvL - 2;
                $cal_three = 100*($this->conf->get("Level.MultipleExpPerLevel")^($cal_four)) + $this->conf->get("Level.PlusMaxExp");
                $this->lvl->reduceLevel($user, 1);
                $i->set("Exp", $cal_two);
                $i->set("MaxExp", $cal_three);
                $i->save();
                //$ncurrentExp = $this->getEXP($user);
                $ncurrentMaxExp = $this->getMaxEXP($user);
                $ncurrentlvl = $this->lvl->getLevel($user);
                // help me how to check if the $reduce is still lesser than 0 then execute reduceExp again until it not lesser than 0
            }elseif($cal_one > 0){
                $i->set("Exp", $cal_one);
                $i->save();
            }
        }
    }


}
?>