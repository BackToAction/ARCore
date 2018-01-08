<?php

namespace ARCore\Task;

use ARCore\ARCore;
use pocketmine\scheduler\PluginTask;

class AntiCheatsTick extends PluginTask
{
    private $plugin;

    public function __construct(ARCore $plugin)
    {
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }

  public function onRun($tick) {
    foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
      if($player->hasPermission("hud.information")) {
        $player->sendTip("\n                                                             §7| §6"."§f". $player->getName()."\n                                                             §7| §e". $this->api->myMoney($player->getName())." Coins"."\n                                                             §7| §3". $this->fac->getPlayerFaction($player->getName()) ."\n\n\n\n\n\n\n\n\n\n\n\n\n");
        } else {
          $player->sendPopup("§5ERROR");
          }
       }

     }

}

?>
