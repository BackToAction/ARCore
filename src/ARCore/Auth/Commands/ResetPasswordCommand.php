<?php
namespace ARCore\Auth\Commands;

use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ResetPasswordCommand extends VanillaCommand {
    public function __construct($name, $plugin) {
        parent::__construct($name, "Reset a player's password", "/rpwd <player>", ["resetpw", "resetpwd", "rpw", "resetpassword"]);
        $this->setPermission("auth.command.resetpassword");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, $currentAlias, array $args) {
        if(!$this->testPermission($sender)) {
            return true;
        }
        if(!isset($args[0])) {
            $sender->sendMessage("/rpwd <player>");
            return false;
        }
        $this->plugin->resetpassword($args[0], $sender);
        return true;
    }

}
