<?php

namespace pets;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pets\main;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use onebone\economyapi\EconomyAPI;
class PetCommand extends PluginCommand {

	public function __construct(main $main, $name) {
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
			$sender->sendMessage("§e/pets spawn [type] §f-§a để mua pet. VD: /pets spawn Bat");
			$sender->sendMessage("§e/pets off §f- để thả tự do cho Pet");
			$sender->sendMessage("§e/pets setname [name] §f-§a Đặt tên cho Pets");
			$sender->sendMessage($this->main->getConfig()->get("PetPrices"));
			return true;
		}
		switch (strtolower($args[0])){
			case "name":
			case "setname":
				if (isset($args[1])){
					unset($args[0]);
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag($name);
					$sender->sendMessage("§aTên thú nuôi của bạn đã đổi thành ".$name);
					$data = new Config($this->main->getDataFolder() . "players/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", $name); 
					$data->save();
				}
				return true;
			break;
			case "help":
				$sender->sendMessage("§e======PetHelp======");
				$sender->sendMessage("§e/pets spawn [type] §f-§a để mua pet. VD: /pets spawn Bat");
				$sender->sendMessage("§e/pets off §f-§a để thả tự do cho Pet");
				$sender->sendMessage("§e/pets setname [name] §f-§a Đặt tên cho Pets");
				$sender->sendMessage($this->main->getConfig()->get("PetPrices"));
				return true;
			break;
			case "off":
				$this->main->disablePet($sender);
				$sender->sendMessage($this->main->getConfig()->get("PetOffMsg"));
			break;
			case "spawn":
				if (isset($args[1])){
					switch ($args[1]){
						case "Dog":
						$price = ($this->main->getConfig()->get("DogCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "WolfPet");
							$pettype = "Dog";
							$sender->sendMessage($this->main->getConfig()->get("SpawnDogMsg"));
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
						$price = ($this->main->getConfig()->get("PigCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "PigPet");
							$pettype = "Pig";
							$sender->sendMessage($this->main->getConfig()->get("SpawnPigMsg"));
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
						$price = ($this->main->getConfig()->get("SheepCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "SheepPet");
							$pettype = "Sheep";
							$sender->sendMessage($this->main->getConfig()->get("SpawnSheepMsg"));
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
						$price = ($this->main->getConfig()->get("RabbitCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "RabbitPet");
							$pettype = "Rabbit";
							$sender->sendMessage($this->main->getConfig()->get("SpawnRabbitMsg"));
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
						$price = ($this->main->getConfig()->get("CatCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "OcelotPet");
							$pettype = "Cat";
							$sender->sendMessage($this->main->getConfig()->get("SpawnCatMsg"));
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
						$price = ($this->main->getConfig()->get("SilverfishCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "SilverfishPet");
							$pettype = "Silverfish";
							$sender->sendMessage($this->main->getConfig()->get("SpawnSilverfishMsg"));
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
						$price = ($this->main->getConfig()->get("MagmaCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "MagmaPet");
							$pettype = "Magma";
							$sender->sendMessage($this->main->getConfig()->get("SpawnMagmaMsg"));
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
						$price = ($this->main->getConfig()->get("BatCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "BatPet");
							$pettype = "Bat";
							$sender->sendMessage($this->main->getConfig()->get("SpawnBatMsg"));
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
						$price = ($this->main->getConfig()->get("BlockCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "BlockPet");
							$pettype = "Block";
							$sender->sendMessage($this->main->getConfig()->get("SpawnBlockMsg"));
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
						$price = ($this->main->getConfig()->get("ChickenCost"));
						    if($r = EconomyAPI::getInstance()->reduceMoney($sender, $price)) { 
							$this->main->changePet($sender, "ChickenPet");
							$pettype = "Chicken";
							$sender->sendMessage($this->main->getConfig()->get("SpawnChickenMsg"));
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
						$sender->sendMessage($this->main->getConfig()->get("PetPrices"));
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
