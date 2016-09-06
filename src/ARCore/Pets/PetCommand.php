<?php
namespace ARCore\Pets;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use ARCore\ARCore;
use pocketmine\utils\TextFormat;
use onebone\economyapi\EconomyAPI;
class PetCommand extends PluginCommand {
	public function __construct(ARCore $main, $name) {
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
		                	$sender->sendMessage("Please Use /pets help");
                         return true;
                          }else{
                           $sender->sendMessage(TextFormat::RED."You do not have permission to use this command");
                    return true;
                }
                         }
		 if($args[0] == "help"){
				if($sender->hasPermission('pets.command.help')){
				$sender->sendMessage("§e======PetHelp======");
				$sender->sendMessage("§b/pets list <page>- To See Pets List");
				$sender->sendMessage("§b/pets buy [type]");
				$sender->sendMessage("§b/pets setname [name]");
                                return true;
				}else{$sender->sendMessage(TextFormat::RED."You do not have permission to use this command");
					    }
				return true;
                 }
               if($args[0] == "setname"){
               	 if($sender->hasPermission("pets.command.name")){
               	 if (isset($args[1])){
               	  $petname = $args[1];
               	  $pet = $this->main->getPet($sender->getName());
               	  $pet->setNameTag("§8".$petname);
               	  $sender->sendMessage(TextFormat::BLUE."Your pets name has been changed to ".$petname."");
					$data = new Config($this->main->getDataFolder() . "PetPlayer/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", "§8$petname"); 
					$data->save();
               	 }
               	 }else{
               	 	$sender->sendMessage(TextFormat::RED."You do not have permission to use this command");
               	 }
               }
			   $blazeprices = $this->main->PetPrices->get("BlazePrices");
			   $pigprices = $this->main->PetPrices->get("PigPrices");
			   $chickenprices = $this->main->PetPrices->get("ChickenPrices");
			   $wolfprices = $this->main->PetPrices->get("WolfPrices");
			   $rabbitprices = $this->main->PetPrices->get("RabbitPrices");
			   $magmaprices = $this->main->PetPrices->get("MagmaPrices");
			   $batprices = $this->main->PetPrices->get("BatPrices");
			   $silverfishprices = $this->main->PetPrices->get("SilverfishPrices");
			   $spiderprices = $this->main->PetPrices->get("SpiderPrices");
			   $cowprices = $this->main->PetPrices->get("CowPrices");
			   $creeperprices = $this->main->PetPrices->get("CreeperPrices");
			   $irongolemprices = $this->main->PetPrices->get("IronGolemPrices");
			   $huskprices = $this->main->PetPrices->get("HuskPrices");
			   $endermanprices = $this->main->PetPrices->get("EndermanPrices");
			   $sheepprices = $this->main->PetPrices->get("SheepPrices");
			   $witchprices = $this->main->PetPrices->get("WitchPrices");
			   $blockprices = $this->main->PetPrices->get("BlockPrices");

			   if(strtolower($args[0]) == "list"){
			   if(!isset($args[1]) || $args[1] == 1){
					   $sender->sendMessage("=== Pets List ===");
					   $sender->sendMessage("blaze : Price = ". $blazeprices);
					   $sender->sendMessage("pig : Price = ". $pigprices);
					   $sender->sendMessage("chicken : Price = ". $chickenprices);
					   $sender->sendMessage("wolf : Price = ". $wolfprices);
					   $sender->sendMessage("rabbit : Price = ". $rabbitprices);
					   $sender->sendMessage("magma : Price = ". $magmaprices);
					   return true;
			   }else{
			   $sender->sendMessage("Usage: /pets list [1 Until 3]");
			   return true;
			}
			   if($args[1] == 2){
					   $sender->sendMessage("=== Pets List ===");
					   $sender->sendMessage("bat : Price = ". $batprices);
					   $sender->sendMessage("silverfish : Price = ". $silverfishprices);
					   $sender->sendMessage("spider : Price = ". $spiderprices);
					   $sender->sendMessage("cow : Price = ". $cowprices);
					   $sender->sendMessage("creeper : Price = ". $creeperprices);
					   $sender->sendMessage("irongolem : Price = ". $irongolemprices);
					   return true;
			   }else{
			   $sender->sendMessage("Usage: /pets list [1 Until 3]");
			   return true;
			}
			   if($args[1] == 3){
					   $sender->sendMessage("=== Pets List ===");
					   $sender->sendMessage("husk : Price = ". $huskprices);
					   $sender->sendMessage("enderman : Price = ". $endermanprices);
					   $sender->sendMessage("sheep : Price = ". $sheepprices);
					   $sender->sendMessage("witch : Price = ". $witchprices);
					   $sender->sendMessage("block : Price = ". $blockprices);
					   return true;
			   }else{
			   $sender->sendMessage("Usage: /pets list [1 Until 3]");
			   return true;
			}
			   }
			
			//Trying Something Bleh

			if($args[0] == "buy"){
				if(isset($args[1])){
					if($args[1] == "blaze"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $blazeprices)){
							$this->main->changePet($sender, "BlazePet");
							$sender->sendMessage("Succesfully Buy Blaze!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Blaze Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "pig"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $pigprices)){
							$this->main->changePet($sender, "PigPet");
							$sender->sendMessage("Succesfully Buy Pig!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Pig Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "chicken"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $chickenprices)){
							$this->main->changePet($sender, "ChickenPet");
							$sender->sendMessage("Succesfully Buy Chicken!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Chicken Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "wolf"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $wolfprices)){
							$this->main->changePet($sender, "WolfPet");
							$sender->sendMessage("Succesfully Buy Wolf!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Wolf Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "rabbit"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $rabbitprices)){
							$this->main->changePet($sender, "RabbitPet");
							$sender->sendMessage("Succesfully Buy Rabbit!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Rabbit Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "magma"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $magmaprices)){
							$this->main->changePet($sender, "MagmaPet");
							$sender->sendMessage("Succesfully Buy Blaze!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Magma Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "bat"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $batrices)){
							$this->main->changePet($sender, "BatPet");
							$sender->sendMessage("Succesfully Buy Bat!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Bat Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "silverfish"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $silverfishprices)){
							$this->main->changePet($sender, "SilverfishPet");
							$sender->sendMessage("Succesfully Buy Silverfish!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Silverfish Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "spider"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $spiderprices)){
							$this->main->changePet($sender, "SpiderPet");
							$sender->sendMessage("Succesfully Buy Spider!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Spider Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "cow"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $cowprices)){
							$this->main->changePet($sender, "CowPet");
							$sender->sendMessage("Succesfully Buy Cow!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Cow Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "Creeper"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $creeperprices)){
							$this->main->changePet($sender, "CreeperPet");
							$sender->sendMessage("Succesfully Buy Creeper!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Creeper Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "irongolem"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $irongolemprices)){
							$this->main->changePet($sender, "IronGolemPet");
							$sender->sendMessage("Succesfully Buy Iron Golem!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Iron Golem Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "husk"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $huskprices)){
							$this->main->changePet($sender, "HuskPet");
							$sender->sendMessage("Succesfully Buy Husk!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Husk Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "enderman"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $endermanprices)){
							$this->main->changePet($sender, "EndermanPet");
							$sender->sendMessage("Succesfully Buy Enderman!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Enderman Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "sheep"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $sheepprices)){
							$this->main->changePet($sender, "SheepPet");
							$sender->sendMessage("Succesfully Buy Sheep!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Sheep Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "witch"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $witchprices)){
							$this->main->changePet($sender, "WitchPet");
							$sender->sendMessage("Succesfully Buy Witch!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Witch Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
					if($args[1] == "block"){
						if($economys = EconomyAPI::getInstance()->reduceMoney($sender, $blockprices)){
							$this->main->changePet($sender, "BlockPet");
							$sender->sendMessage("Succesfully Buy Block!");
						}else{
							switch($economys){
								case EconomyAPI::RET_INVALID:
								$sender->sendMessage("You Unable To Buy Block Due To Not Enough Coins");
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§6-ERROR!"));
								break;
							}
						}
					}
				}
			}//e530a7d7aea37#8828888passwordhash2112dwbLCEcorps011900121
/*
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
                                                
                                                
                        }*/                            
        }
}

