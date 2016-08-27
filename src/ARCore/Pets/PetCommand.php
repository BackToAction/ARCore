<?php
namespace ARCore\Pets;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use ARCore\Pets\main;
use pocketmine\utils\TextFormat;
class PetCommand extends PluginCommand {
	public function __construct(main $main, $name) {
		parent::__construct(
				$name, $main
		);
		$this->main = $main;
		$this->setPermission("pets.command");
		$this->setAliases(array("pet"));
	}
	public function execute(CommandSender $sender, $currentAlias, array $args) {
		  
                         if (!isset($args[0])) {
                          if($sender->hasPermission('pets.command')){
			$this->main->togglePet($sender);
                         return true;
                          }else{
                           $sender->sendMessage(TextFormat::RED."You do not have permission to use this command");
			
                    return true;
                }
                         }
		 if($args[0] == "help"){
				if($sender->hasPermission('pets.command.help')){
				$sender->sendMessage("§e======PetHelp======");
				$sender->sendMessage("§b/pets to Spawn your Pet");
				$sender->sendMessage("§b/pets type [type]");
				$sender->sendMessage("§bTypes: blaze, pig, chicken, wolf, rabbit, magma, bat, silverfish, spider, cow, creeper, irongolem, husk, enderman, sheep, witch, block");
                                return true;
				}else{$sender->sendMessage(TextFormat::RED."You do not have permission to use this command");
					    }
				return true;
                 }
               if($args[0] == "name"){
               	 if($sender->hasPermission("pets.command.name")){
               	 if (isset($args[1])){
               	  $petname = $args[1];
               	  $pet = $this->main->getPet($sender->getName());
               	  $pet->setNameTag($petname);
               	  $sender->sendMessage(TextFormat::BLUE."Your pets name has been changed to ".$petname."");
               	 }
               	 }else{
               	 	$sender->sendMessage(TextFormat::RED."You do not have permission to use this command");
               	 }
               }
               	
			if($args[0] == "type"){
				if (isset($args[1])){
					if($args[1] == "wolf"){
							if ($sender->hasPermission("pets.type.wolf")){
								$this->main->changePet($sender, "WolfPet");
								$sender->sendMessage("Your pet has changed to Wolf!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for dog pet!");
								return true;
							}
                                        }
						if($args[1] == "chicken"){
							if ($sender->hasPermission("pets.type.chicken")){
								$this->main->changePet($sender, "ChickenPet");
								$sender->sendMessage("Your pet has changed to Chicken!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for chicken pet!");
								return true;
							}
                                                }
						if($args[1] == "pig"){
							if ($sender->hasPermission("pets.type.pig")){
								$this->main->changePet($sender, "PigPet");
								$sender->sendMessage("Your pet has changed to Pig!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for pig pet!");
								return true;
							}
                                                }
						if($args[1] == "blaze"){
							if ($sender->hasPermission("pets.type.blaze")){
								$this->main->changePet($sender, "BlazePet");
								$sender->sendMessage("Your pet has changed to Blaze!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for blaze pet!");
								return true;
							}
                                                }
						if($args[1] == "magma"){
							if ($sender->hasPermission("pets.type.magma")){
								$this->main->changePet($sender, "MagmaPet");
								$sender->sendMessage("Your pet has changed to Magma!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for blaze pet!");
								return true;
							}
                                                }
						if($args[1] == "rabbit"){
							if ($sender->hasPermission("pets.type.rabbit")){
								$this->main->changePet($sender, "RabbitPet");
								$sender->sendMessage("Your pet has changed to Rabbit!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for rabbit pet!");
								return true;
							}
                                                }
						if($args[1] == "bat"){
							if ($sender->hasPermission("pets.type.bat")){
								$this->main->changePet($sender, "BatPet");
								$sender->sendMessage("Your pet has changed to Bat!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for bat pet!");
								return true;
							}
                                                }
						if($args[1] == "silverfish"){
							if ($sender->hasPermission("pets.type.silverfish")){
								$this->main->changePet($sender, "SilverfishPet");
								$sender->sendMessage("Your pet has changed to Siverfish!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Silverfish pet!");
								return true;
							}
						
							}
								if($args[1] == "spider"){
							if ($sender->hasPermission("pets.type.spider")){
								$this->main->changePet($sender, "SpiderPet");
								$sender->sendMessage("Your pet has changed to Spider!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for spider pet!");
								return true;
							}
                                                }
                                		if($args[1] == "cow"){
							if ($sender->hasPermission("pets.type.cow")){
								$this->main->changePet($sender, "CowPet");
								$sender->sendMessage("Your pet has changed to Cow!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for cow pet!");
								return true;
							}
                                                }
						if($args[1] == "creeper"){
							if ($sender->hasPermission("pets.type.creeper")){
								$this->main->changePet($sender, "CreeperPet");
								$sender->sendMessage("Your pet has changed to Creeper!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for creeper pet!");
								return true;
							}
                                                }
					                 if($args[1] == "irongolem"){
							if ($sender->hasPermission("pets.type.irongolem")){
								$this->main->changePet($sender, "IronGolemPet");
								$sender->sendMessage("Your pet has changed to Iron Golem!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Iron Golem pet!");
								return true;
							}
                                                }
			                    if($args[1] == "husk"){
							if ($sender->hasPermission("pets.type.husk")){
								$this->main->changePet($sender, "HuskPet");
								$sender->sendMessage("Your pet has changed to Husk!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Husk pet!");
								return true;
							}
                                                }
                                           if($args[1] == "enderman"){
							if ($sender->hasPermission("pets.type.enderman")){
								$this->main->changePet($sender, "EndermanPet");
								$sender->sendMessage("Your pet has changed to Enderman!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Enderman pet!");
								return true;
							}
                                                }
                                                 if($args[1] == "sheep"){
							if ($sender->hasPermission("pets.type.sheep")){
								$this->main->changePet($sender, "SheepPet");
								$sender->sendMessage("Your pet has changed to Sheep!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Sheep pet!");
								return true;
							}
                                                }
                                                 if($args[1] == "witch"){
							if ($sender->hasPermission("pets.type.witch")){
								$this->main->changePet($sender, "WitchPet");
								$sender->sendMessage("Your pet has changed to Witch!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Witch pet!");
								return true;
							}
                                                }
                                                if($args[1] == "block"){
							if ($sender->hasPermission("pets.type.block")){
								$this->main->changePet($sender, "BlockPet");
								$sender->sendMessage("Your pet has changed to Block!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Block pet!");
								return true;
							}
                                                }
	}
                                                
                                                
                        }                            
        }
}
