<?php

namespace ARCore\ChatFilter;

use pocketmine\utils\TextFormat;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\Utils;
use pocketmine\Player;

/**
 * Clears the recent chat messages
 */
class ChatFilterTask extends PluginTask {

    /**
     * Constructs the task and sets the owner
     *
     * @param PluginBase $owner The owner
     */
    public function __construct($owner) {
        $this->owner = $owner;
    }

    /**
     * When the task is ran
     *
     * @param  integer $currentTick The current tick
     * @return null                 Nothing
     */
    public function onRun($currentTick) {
        $this->getOwner()->filter->clearRecentChat();
    }
}