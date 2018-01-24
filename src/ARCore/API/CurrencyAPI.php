<?php

namespace ARCore\API;

use pocketmine\utils\Config;
use pocketmine\utils\Utils;
use ARCore\ARCore;
use ARCore\API\DatabaseAPI;

class CurrencyAPI {

    // Using My Simplified API From BTACore-PMMP
    // modified a bit for ARCore Comparebility

    static public $instance;

	public function __construct(ARCore $plugin){
        $this->plugin = $plugin;
        $this->database = new DatabaseAPI::getInstance();
    }

    static public function getInstance(){// allow to get CurrencyAPI Instance.
        if (is_null(self::$instance)) {// if there is no instance
             self::$instance = new self();// make new instance
        }
        return self::$instance; // else (if this have instance) return instance
    }

    public function getCoins($user){ // simple and friendly use API... Ez To Learn
        $i = $this->database->getPlayerDatabase($user);
        $result = $i->get("Coins");
        return $result;
        $i->save();
    }

    public function setCoins($user, $coins){
        $i = $this->database->getPlayerDatabase($user);
        if($coins >= 0){
            $result = $i->set("Coins", $coins);
            return $result;
            $i->save();
        }
    }

    public function addCoins($user, $coins){
        $i = $this->database->getPlayerDatabase($user);
        if($coins >= 0){// the numbers is not negative, must be positive
            $lastest = $i->get("Coins");
            $i->set("Coins", $lastest + $coins);
            $i->save();
        }
    }

    public function reduceCoins($user, $coins){
        $i = $this->database->getPlayerDatabase($user);
        if($coins >= 0){
            $lastest = $i->get("Coins");
            if($coins <= $lastest){
                $i->set("Coins", $lastest - $coins);
                $i->save();
            }else{
                return -1;// later if want to make shop, ensure to get this if the player coins isn't sufficient which would enter as false
            }
        }
    }




}

?>