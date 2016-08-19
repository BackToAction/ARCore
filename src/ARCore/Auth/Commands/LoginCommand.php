<?php
namespace ARCore\Auth\Commands;

use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class LoginCommand extends VanillaCommand {
    public function __construct($name, $plugin) {
        parent::__construct($name, "Login to your account", "/log <password>", ["login"]);
        $this->setPermission("auth.command.log");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, $currentAlias, array $args) {
        if(!$this->testPermission($sender)) {
            return true;
        }
        if(!$sender instanceof Player) {
            $sender->sendMessage("§cYou must use the command in-game.");
            return false;
        }
        if(!isset($args[0])) {
            $sender->sendMessage("/log <password>");
            return false;
        }
        $this->plugin->login($sender, $args[0]);
        return true;
    }

}
