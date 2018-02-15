<?php

/**
 * Copyrights 2018 @HyGlobalHD (https://github.com/HyGlobalHD)
 * This Code Is Made By @HyGlobalHD
 * Please Give Credits For Using This Code As Reference.
 * Reference: HyGlobalHD's Theory-RPG (https://github.com/SLIMENATIONS/Theory-RPG)
 */
namespace ARCore\API;

class StatsAPI {

    /** Todo
     *  - Stats = Attribute Of The Player ..
     */

    static public $instance;

    public function __construct(ARCore $plugin){
        $this->plugin = $plugin;
        $this->database = new DatabaseAPI::getInstance();
        $this->conf = new Config($this->getDataFolder() . "config.yml");
        $this->level = new LevelAPI::getInstance();
    }
    
    static public function getInstance(){
        if (is_null(self::$instance)) {
                self::$instance = new self();
        }
        return self::$instance;
    }

     public function getStr($user){
         $i = $this->database->getPlayerDatabase($user);
         $result = $i->get("Stats.str");
         return $result;
     } // get user str.

     public function addStr($user, $ap){}

    public function calcBaseDmg($user){ // not the user's final damage; this is just the base damage.
        $str = $this->getStr($user);
    }

    /**
     *  calculation for user attack base: str has relation with user level, item attr, equipment.
     *  // delayed.
     * 
     * 
     */

}
?>