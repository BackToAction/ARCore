<?php

namespace ARCore\API;

use ARCore\ARCore;
use ARCore\API\DatabaseAPI;
use pocketmine\utils\Config;
use pocketmine\utils\Utils;

class LevelAPI {

    static public $instance;

    public function __construct(ARCore $plugin){
        $this->plugin = $plugin;
        $this->database = new DatabaseAPI::getInstance();
    }




}

?>