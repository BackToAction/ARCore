<?php
namespace ARCore\Auth\Tasks;

use pocketmine\scheduler\PluginTask;

class PopupTipTick extends PluginTask {
    public function __construct($plugin) {
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }

    public function onRun($currentTick) {
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
            if(!$this->plugin->isAuthenticated($player) && !isset($this->plugin->confirmPassword[strtolower($player->getName())])) {
                if($this->plugin->auth->get("popup")) {
                    if($this->plugin->isRegistered($player->getName())) {
                        $player->sendPopup($this->plugin->auth->get("login-popup"));
                    } else {
                        $player->sendPopup($this->plugin->auth->get("register-popup"));
                    }
                }
            }
        }
    }

}
