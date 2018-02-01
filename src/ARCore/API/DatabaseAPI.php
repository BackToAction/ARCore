<?php

namespace ARCore\API;

use pocketmine\utils\Config;
use pocketmine\utils\Utils;
use ARCore\ARCore;

class DatabaseAPI {

    static private $instance;

    public function __construct(ARCore $plugin){
        $this->plugin = $plugin;
        $this->conf = new Config($this->getDataFolder() . "config.yml");
    }
    
    static private function getInstance(){ // make it private function
        if (is_null(self::$instance)) {
                self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPlayerDatabase($user){
        $ign = strtolower($user);
        $hasher = hash(sha256, $ign);// hmm?? just bored.
        if($this->conf->get("Enable%FGC") == true){// Enable FGC To Use It!!
            $fgc = $this->conf->get("FGC.Feature");
            $result = new Config($this->plugin->getDataFolder() . "\\data\\players\\" . $hasher . ".yml", CONFIG::YAML, array(
                "Username" => $ign,
                "Coins" => 0,
                "Level" => 1,
                "Exp" => 0, "MaxExp" => 100,
                "Class" => 0,
                "Health" => 10,
                "Mana" => 10,
                "AttrPoint" => 0, "SkillPoint" => 0,
                "Stats" => [
                    "str" => 5, "int" => 5, "dex" => 5, "luck" => 5,
                ],
                "Skills" => [
                    "Normal Attack" => "Just A Normal Attack",
                ],
                "Title" => [
                    "New Lancer" => "You Still Confuse With The Server As You Still New.",
                ],
                "Description" => "Tell A Bit About Yourself.",
                "Likes" => 0,
                "Achievements" => 0,
                "WarnPoints" => 0,
                "$fgc" => "",
                "Friends" => [],
            ));
            return $result;
        }else{
            $result = new Config($this->plugin->getDataFolder() . "\\data\\players\\" . $hasher . ".yml", CONFIG::YAML, array(
                "Username" => $ign,
                "Coins" => 0,
                "Level" => 1,
                "Exp" => 0,
                "Class" => 0,
                "Health" => 10,
                "Mana" => 10,
                "Stats" => [
                    "str" => 5, "int" => 5, "dex" => 5, "luck" => 5,
                ],
                "Skills" => [
                    "Normal Attack" => "Just A Normal Attack",
                ],
                "Title" => [
                    "New Lancer" => "You Still Confuse With The Server As You Still New.",
                ],
                "Description" => "Tell A Bit About Yourself",
                "Likes" => 0,
                "Achievements" => 0,
                "WarnPoints" => 0,
                "Friends" => [],
            ));
            return $result;
        }
    }

    public function getPlayerParty($party_name){
        // todo
        // put it in \\data\\partys\\
        // party is a small branch of FGC which doesn't effect FGC
        // it enable player to form a party of 8 maximum,
        // for: enable to enter a specific instance(eg: dungeon) and clear it together
        // todo 
    }

    public function getEntityDatabase($entity){
        //... Todo
        // Design Note: For Making A Custom Mob.
    }


}

?>