<?php

namespace ARCore\Pets;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use ARCore\ARCore;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use onebone\economyapi\EconomyAPI;
class PetCommand extends PluginCommand {

  public $main;

	public function __construct(ARCore $main, $name) {
		parent::__construct(
				$name, $main
		);
		$this->main = $main;
		$this->setPermission("pets.command");
		$this->setAliases(array("pets"));
	}

	public function execute(CommandSender $sender, $currentAlias, array $args) {
	if($sender->hasPermission('pets.command')){
		if (!isset($args[0])) {
			$sender->sendMessage("§b======PetHelp======");
			$sender->sendMessage("§e/pets spawn [type] §f- Spawn your pets");
			$sender->sendMessage("§e/pets off §f- Turn off your pets");
			$sender->sendMessage("§e/pets setname [name] §f- Name your pets);
			$sender->sendMessage("§e/pets prices §f- List Pet Price");
			return true;
		}
		switch (strtolower($args[0])){
			case "name":
			case "setname":
				if (isset($args[1])){
					unset($args[0]);
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag($name);
					$sender->sendMessage("§aSucced Set Pets Name To ".$name);
					$data = new Config($this->main->getDataFolder() . "PetPlayer/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", "§8$name"); 
					$data->save();
				}
				return true;
			break;
			case "help":
				$sender->sendMessage("§b======PetHelp======");
			        $sender->sendMessage("§e/pets spawn [type] §f- Spawn your pets");
			        $sender->sendMessage("§e/pets off §f- Turn off your pets");
			        $sender->sendMessage("§e/pets setname [name] §f- Name your pets);
			        $sender->sendMessage("§e/pets prices §f- List Pet Price");
				return true;
			case "prices":
			$sender->sendMessage($this->main->PetPrices->get("PetPrices"));
				return true;
			break;
			case "off":
				$this->main->disablePet($sender);
			$sender->sendMessage($this->main->PetPrices->get("PetOffMsg"));
			break;
			case "spawn":
				if (isset($args[1])){
					switch ($args[1]){
						case "Dog":
						$price = ($this->main->PetPrices->get("DogCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "WolfPet");
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag("§8".$sender->getName()."§8's Pet");
					$data = new Config($this->main->getDataFolder() . "PetPlayer/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", "§8$name"); 
					$data->save();
							$pettype = "Dog";
							$sender->sendMessage($this->main->PetPrices->get("SpawnDogMsg"));
							return true;} 
							else {

						    switch($r){
							case EconomyAPI::RET_INVALID:
							
								$sender->sendMessage("§6-You do not have enough Money to get the Pet! Need $$price");
								break;
							case EconomyAPI::RET_CANCELLED:
							
								$sender->sendMessage("§6-ERROR!");
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage("§6-ERROR!");
								break;
						}
					}
							break;
						case "Pig":
						$price = ($this->main->PetPrices->get("PigCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "PigPet");
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag("§8".$sender->getName()."§8's Pet");
					$data = new Config($this->main->getDataFolder() . "PetPlayer/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", "§8$name"); 
					$data->save();
							$pettype = "Pig";
							$sender->sendMessage($this->main->PetPrices->get("SpawnPigMsg"));
							return true;} 
							else {

						    switch($r){
							case EconomyAPI::RET_INVALID:
							
								$sender->sendMessage("§6-You do not have enough Money to get the Pet! Need $$price");
								break;
							case EconomyAPI::RET_CANCELLED:
							
								$sender->sendMessage("§6-ERROR!");
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage("§6-ERROR!");
								break;
						}
					}
						break;
						case "Sheep":
						$price = ($this->main->PetPrices->get("SheepCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "SheepPet");
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag("§8".$sender->getName()."§8's Pet");
					$data = new Config($this->main->getDataFolder() . "PetPlayer/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", "§8$name"); 
					$data->save();
							$pettype = "Sheep";
							$sender->sendMessage($this->main->PetPrices->get("SpawnSheepMsg"));
							return true;} 
							else {

						    switch($r){
							case EconomyAPI::RET_INVALID:
							
								$sender->sendMessage("§6-You do not have enough Money to get the Pet! Need $$price");
								break;
							case EconomyAPI::RET_CANCELLED:
							
								$sender->sendMessage("§6-ERROR!");
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage("§6-ERROR!");
								break;
						}
					}
						break;
						case "Rabbit":
						$price = ($this->main->PetPrices->get("RabbitCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "RabbitPet");
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag("§8".$sender->getName()."§8's Pet");
					$data = new Config($this->main->getDataFolder() . "PetPlayer/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", "§8$name"); 
					$data->save();
							$pettype = "Rabbit";
							$sender->sendMessage($this->main->PetPrices->get("SpawnRabbitMsg"));
							return true;} 
							else {

						    switch($r){
							case EconomyAPI::RET_INVALID:
							
								$sender->sendMessage("§6-You do not have enough Money to get the Pet! Need $$price");
								break;
							case EconomyAPI::RET_CANCELLED:
							
								$sender->sendMessage("§6-ERROR!");
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage("§6-ERROR!");
								break;
						}
					}
						break;
						case "Cat":
						$price = ($this->main->PetPrices->get("CatCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "OcelotPet");
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag("§8".$sender->getName()."§8's Pet");
					$data = new Config($this->main->getDataFolder() . "PetPlayer/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", "§8$name"); 
					$data->save();
							$pettype = "Cat";
							$sender->sendMessage($this->main->PetPrices->get("SpawnCatMsg"));
							return true;} 
							else {

						    switch($r){
							case EconomyAPI::RET_INVALID:
							
								$sender->sendMessage("§6-You do not have enough Money to get the Pet! Need $$price");
								break;
							case EconomyAPI::RET_CANCELLED:
							
								$sender->sendMessage("§6-ERROR!");
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage("§6-ERROR!");
								break;
						}
					}
						break;
						case "Silverfish":
						$price = ($this->main->PetPrices->get("SilverfishCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "SilverfishPet");
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag("§8".$sender->getName()."§8's Pet");
					$data = new Config($this->main->getDataFolder() . "PetPlayer/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", "§8$name"); 
					$data->save();
							$pettype = "Silverfish";
							$sender->sendMessage($this->main->PetPrices->get("SpawnSilverfishMsg"));
							return true;} 
							else {

						    switch($r){
							case EconomyAPI::RET_INVALID:
							
								$sender->sendMessage("§6-You do not have enough Money to get the Pet! Need $$price");
								break;
							case EconomyAPI::RET_CANCELLED:
							
								$sender->sendMessage("§6-ERROR!");
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage("§6-ERROR!");
								break;
						}
					}
						break;
						case "Magma":
						$price = ($this->main->PetPrices->get("MagmaCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "MagmaPet");
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag("§8".$sender->getName()."§8's Pet");
					$data = new Config($this->main->getDataFolder() . "PetPlayer/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", "§8$name"); 
					$data->save();
							$pettype = "Magma";
							$sender->sendMessage($this->main->PetPrices->get("SpawnMagmaMsg"));
							return true;} 
							else {

						    switch($r){
							case EconomyAPI::RET_INVALID:
							
								$sender->sendMessage("§6-You do not have enough Money to get the Pet! Need $$price");
								break;
							case EconomyAPI::RET_CANCELLED:
							
								$sender->sendMessage("§6-ERROR!");
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage("§6-ERROR!");
								break;
						}
					}
						break;
						case "Bat":
						$price = ($this->main->PetPrices->get("BatCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "BatPet");
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag("§8".$sender->getName()."§8's Pet");
					$data = new Config($this->main->getDataFolder() . "PetPlayer/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", "§8$name"); 
					$data->save();
							$pettype = "Bat";
							$sender->sendMessage($this->main->PetPrices->get("SpawnBatMsg"));
							return true;} 
							else {

						    switch($r){
							case EconomyAPI::RET_INVALID:
							
								$sender->sendMessage("§6-You do not have enough Money to get the Pet! Need $$price");
								break;
							case EconomyAPI::RET_CANCELLED:
							
								$sender->sendMessage("§6-ERROR!");
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage("§6-ERROR!");
								break;
						}
					}
						break;
						case "Block":
						$price = ($this->main->PetPrices->get("BlockCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "BlockPet");
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag("§8".$sender->getName()."§8's Pet");
					$data = new Config($this->main->getDataFolder() . "PetPlayer/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", "§8$name"); 
					$data->save();
							$pettype = "Block";
							$sender->sendMessage($this->main->PetPrices->get("SpawnBlockMsg"));
							return true;} 
							else {

						    switch($r){
							case EconomyAPI::RET_INVALID:
							
								$sender->sendMessage("§6-You do not have enough Money to get the Pet! Need $$price");
								break;
							case EconomyAPI::RET_CANCELLED:
							
								$sender->sendMessage("§6-ERROR!");
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage("§6-ERROR!");
								break;
						}
					}
						break;
						case "Chicken":
						$price = ($this->main->PetPrices->get("ChickenCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "ChickenPet");
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag("§8".$sender->getName()."§8's Pet");
					$data = new Config($this->main->getDataFolder() . "PetPlayer/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", "§8$name"); 
					$data->save();
							$pettype = "Chicken";
							$sender->sendMessage($this->main->PetPrices->get("SpawnChickenMsg"));
							return true;} 
							else {

						    switch($r){
							case EconomyAPI::RET_INVALID:
							
								$sender->sendMessage("§6-You do not have enough Money to get the Pet! Need $$price");
								break;
							case EconomyAPI::RET_CANCELLED:
							
								$sender->sendMessage("§6-ERROR!");
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage("§6-ERROR!");
								break;
						}
					}
						break;
					default:
						$sender->sendMessage("§b/pets spawn [type]");
					break;	
					return true;
					}
				}
			break;
		}
		return true;
	}
	}
}
