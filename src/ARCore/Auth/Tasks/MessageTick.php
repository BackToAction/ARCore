<?php
namespace ARCore\Auth\Tasks;

use pocketmine\scheduler\PluginTask;

/*
* Code From SimpleAuths And Rewrite By ArchRPG Teams.
*/
class MessageTick extends PluginTask {
    public function __construct($plugin) {
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }

    public function onRun($currentTick) {
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
            if(!$this->plugin->isAuthenticated($player) && !isset($this->plugin->confirmPassword[strtolower($player->getName())]) && isset($this->plugin->messagetick[strtolower($player->getName())])) {
                if($this->plugin->messagetick[strtolower($player->getName())] == $this->plugin->auth->get("seconds-til-next-message")) {
                    $this->plugin->messagetick[strtolower($player->getName())] = 0;
                    if($this->plugin->isRegistered($player->getName())) {
                        $player->sendMessage($this->plugin->auth->get("login"));
                    } else {
                        $player->sendMessage($this->plugin->auth->get("register"));
                    }
                } else {
                    $this->plugin->messagetick[strtolower($player->getName())] += 1;
                }
            }
        }
    }

}
