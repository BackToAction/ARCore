<?php

namespace ARCore\Commands;

use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use ARCore\ARCore;

class ARParticleCommand extends VanillaCommand {
    public function __construct($name, $plugin) {
        parent::__construct($name, "Particle Commands", "/arparticles", ["arparticles"]);
        $this->setPermission("arc.commands.arparticle");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, Command $command, $label, array $args) {
        $subcommand = strtolower($command->getName('arparticles'));
        switch ($subcommand) {
            case "give";
                if(count($args) < 1){
                    array_unshift($args, $sender->getDisplayName());
                }

                if ($sender->hasPermission("arparticles")) {
                    if($this->plugin->giveParticle(...$args)) {
                        $sender->sendMessage(TextFormat::BLUE . ' ' . $args[0] . ' has a new particle effect!');
                    } else {
                        $sender->sendMessage(TextFormat::BLUE . ' Unable to give ' . $args[0] . ' a new particle effect!');
                    }
                    return true;
                }

                $sender->sendMessage(TextFormat::RED . " You don't have permissions to do that...");
                return true;
            case "remove":
                if(count($args) < 1){
                    array_unshift($args, $sender->getDisplayName());
                }

                if ($sender->hasPermission("arparticles")) {
                    $args[] = true;
                    if($this->removeParticle(...$args)) {
                        $sender->sendMessage(TextFormat::RED . ' ' . $args[0] . '\'s particle effect was removed!');
                    } else {
                        $sender->sendMessage(TextFormat::RED . ' Unable to remove ' . $args[0] . '\'s particle effect!');
                    }
                    return true;
                }

                $sender->sendMessage(TextFormat::RED . " You don't have permissions to do that...");
                return true;
            case "help":
                $sender->sendMessage(TextFormat::GREEN . ' Available commands: give, remove');
                return true;
                break;
            default:
                return false;
        }
    }

}

?>
