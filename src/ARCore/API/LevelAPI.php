<?php

namespace ARCore\API;

use ARCore\ARCore;
use ARCore\API\DatabaseAPI;
use pocketmine\utils\Config;
use pocketmine\utils\Utils;

class LevelAPI {

    // Design Note For LevelAPI
    /**
     *  I'm Not Sure If I Wanted To Add ExpAPI Seperately Or In This LevelAPI
     *  However To Ensure I Do A OOD(Object Oriented Design) For The Rewrite I'll Make ExpAPI Seperate From LevelAPI Code.
     *  Note: This Is Very Interesting... 
     */

    static public $instance;

    public function __construct(ARCore $plugin){
        $this->plugin = $plugin;
        $this->database = new DatabaseAPI::getInstance();
        $this->conf = new Config($this->getDataFolder() . "config.yml");
        $this->exp = new ExpAPI::getInstance();
    }

    static public function getInstance(){// allow to get CurrencyAPI Instance.
        if (is_null(self::$instance)) {// if there is no instance
             self::$instance = new self();// make new instance
        }
        return self::$instance; // else (if this have instance) return instance
    }

    public function getMaxLevel(){
        $i = $this->conf->get("Level.Max%Level");
        return $i;
    }

    public function getLevel($user){
        $i = $this->database->getPlayerDatabase($user);
        $result = $i->get("Level");
        if($result >= 0){
            if($result <= $this->getMaxLevel()){
                return $result;
            }else{
                return $this->getMaxLevel();
            }
        }else{
            return 0;
        }
    }

    public function reduceLevel($user, $reduce_level){ // will be un-used. xP
        $i = $this->getLevel($user);
        $ii = $this->database->getPlayerDatabase($user);
        if($i > $reduce_level){
            $ii->set("Level", $i - $reduce_level);
            $ii->save();
        }elseif($i == $reduce_level){
            $ii->set("Level", 1);
            $ii->save();
        }elseif($i < $reduce_level){
            $ii->set("Level", 1);
            $ii->save();
        }
    }

    public function LevelUp($user){
        $i = $this->database->getPlayerDatabase($user);
        $restrition = $this->conf->get("Level.Max%Level");
        $getLevel = $this->getLevel($user);
        // todo.
        if($getLevel >= $restrition){
            return true;
        }elseif($getLevel < $restrition){
            // todo
            $cal = $getLevel + 1;
            $i->set("Level", $cal);
            $i->save();
        }
    }





}

?>