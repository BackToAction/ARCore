<?php

namespace ARCore\Commands;

use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use ARCore\ARCore;

class AntiHackCommand extends VanillaCommand {
    public function __construct($name, $plugin) {
        parent::__construct($name, "Anti Hack Commands", "/antihack", ["antihack"]);
        $this->setPermission("arc.commands.antihack");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, Command $command, $label, array $args) {
        $subcommand = strtolower(array_shift($args));
        switch ($subcommand) {
            default:
                return false;
        }
    }

}


