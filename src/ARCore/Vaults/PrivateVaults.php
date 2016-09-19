<?php

/**
 * 
 * Copyright (C) 2016 CraftYourBukkit
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @author CraftYourBukkit
 * @link https://twitter.com/CraftYourBukkit
 *
 */

namespace ARCore/Vaults;

use pocketmine\block\Block;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\inventory\ChestInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Chest;
use pocketmine\tile\Tile;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\item\ItemBlock;
use pocketmine\permission\Permission;

class PrivateVaults extends PluginBase implements Listener {
	
	public $using = array();
	
	public function onLoad() {
		if(@array_shift($this->getDescription()->getAuthors()) != "5b4879476c6f62616c48442c47616d6572587a61766965722c4164616d313630392c4e6575726f42696e64732c536b756c6c33785d") {
			$this->setEnabled(false);
		}
	}
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder() . "players/");
		for($i = 0; $i < 51; $i++) {
			$pvperms = new Permission("pv.vault." . $i, "PrivateVaults permission", "op");
			$this->getServer()->getPluginManager()->addPermission($pvperms);
		}
	}

	public function onJoin(PlayerJoinEvent $event) {
		$this->using[strtolower($event->getPlayer()->getName())] = null;
	}

	public function hasPrivateVault($player) {
		if($player instanceof Player) {
			$player = $player->getName();
		}
		$player = strtolower($player);
		return is_file($this->getDataFolder() . "players/" . $player . ".yml");
	}

	public function createVault($player, $number) {
		if($player instanceof Player) {
			$player = $player->getName();
		}
		$player = strtolower($player);
		$cfg = new Config($this->getDataFolder() . "players/" . $player . ".yml", Config::YAML);
		$cfg->set("items", array());
		for ($i = 0; $i < 26; $i++) {
			$cfg->setNested("$number.items." . $i, array(0, 0, 0, array()));
		}
		$cfg->save();
	}

	public function loadVault(Player $player, $number) {
		$itemblock = Item::fromString("chest");
		$block = $itemblock->getBlock();
		$player->getLevel()->setBlock(new Vector3($player->getX(), 128, $player->getZ()), $block);
		$nbt = new CompoundTag("", [
			new ListTag("Items", []),
			new StringTag("id", Tile::CHEST),
			new IntTag("x", $player->getX()),
			new IntTag("y", $player->getY()),
			new IntTag("z", $player->getZ())
		]);
		$nbt->Items->setTagType(NBT::TAG_Compound);
		$tile = Tile::createTile("Chest", $player->getLevel()->getChunk($player->getX() >> 4, $player->getZ() >> 4), $nbt);
		if($player instanceof Player) {
			$player = $player->getName();
		}
		$player = strtolower($player);
		$cfg = new Config($this->getDataFolder() . "players/" . $player . ".yml", Config::YAML);
		$tile->getInventory()->clearAll();
		for ($i = 0; $i < 26; $i++) {
			$ite = $cfg->getNested("$number.items." . $i);
			$item = Item::get($ite[0]);
			$item->setDamage($ite[1]);
			$item->setCount($ite[2]);
			foreach ($ite[3] as $key => $en) {
				$enchantment = Enchantment::getEnchantment($en[0]);
				$enchantment->setLevel($en[1]);
				$item->addEnchantment($enchantment);
			}
			$tile->getInventory()->setItem($i, $item);
		}
		return $tile->getInventory();
	}

	public function onInventoryClose(InventoryCloseEvent $event) {
		$inventory = $event->getInventory();
		$player = $event->getPlayer();
		if($inventory instanceof ChestInventory) {
			if($this->using[strtolower($player->getName())] !== null) {
				if($player instanceof Player) {
					$player = $player->getName();
				}
				$player = strtolower($player);
				$cfg = new Config($this->getDataFolder() . "players/" . $player . ".yml", Config::YAML);
				for ($i = 0; $i < 26; $i++) {
					$item = $inventory->getItem($i);
					$id = $item->getId();
					$damage = $item->getDamage();
					$count = $item->getCount();
					$enchantments = $item->getEnchantments();
					$ens = array();
					foreach ($enchantments as $en) {
						$ide = $en->getId();
						$level = $en->getLevel();
						array_push($ens, array($ide, $level));
					}
					$number = $this->using[strtolower($event->getPlayer()->getName())];
					$cfg->setNested("$number.items." . $i, array($id, $damage, $count, $ens));
					$cfg->save();
				}
				$realChest = $inventory->getHolder();
				$event->getPlayer()->getLevel()->setBlock(new Vector3($realChest->getX(), 128, $realChest->getZ()), Block::get(Block::AIR));
				$this->using[strtolower($event->getPlayer()->getName())] = null;
			}
		}
	}

	public function saveVault($player, $inventory, $number) {
		if($player instanceof Player) {
			$player = $player->getName();
		}
		$player = strtolower($player);
		
		if($inventory instanceof ChestInventory) {
			$cfg = new Config($this->getDataFolder() . "players/" . $player . ".yml", Config::YAML);
			for ($i = 0; $i < 26; $i++) {
				$item = $inventory->getItem($i);
				$id = $item->getId();
				$damage = $item->getDamage();
				$count = $item->getCount();
				$enchantments = $item->getEnchantments();
				$ens = array();
				
				foreach ($enchantments as $en) {
					$id = $en->getId();
					$level = $en->getLevel();
					array_push($ens, array($id, $level));
				}
				
				$cfg->setNested("$number.items." . $i, array($id, $damage, $count, $ens));
				$cfg->save();
			}
			
			$realChest = $inventory->getHolder();
			$realChest->getLevel()->setBlock(new Vector3($realChest->getX(), 128, $realChest->getZ()), Block::get(Block::AIR));
		}
	}

	public function onQuit(PlayerQuitEvent $event) {
		if($this->using[strtolower($event->getPlayer()->getName())] !== null) {
			$chest = $event->getPlayer()->getLevel()->getTile(new Position($event->getPlayer()->x, $event->getPlayer()->y, $event->getPlayer()->z));
			if($chest instanceof Chest) {
				$inv = $chest->getInventory();
				$this->saveVault($event->getPlayer(), $inv, $this->using[strtolower($event->getPlayer()->getName())]);
				unset($this->using[strtolower($event->getPlayer()->getName())]);
			}
		}
	}

	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
		if($sender instanceof Player) {
			switch ($cmd->getName()) {
				case "pv":
					if(!empty($args[0])) {
						if($args[0] === "about") {
							$sender->sendMessage("§6PrivateVaults§7>> Made by §bCraftYourBukkit");
							$sender->sendMessage("§6PrivateVaults§7>> §bTwitter: @CraftYourBukkit");
							return true;
						}
					}
					
					if($this->hasPrivateVault($sender)) {
						if(empty($args[0])) {
							if($sender->hasPermission("pv.vault.1")) {
								$args[0] = 1;
								$sender->addWindow($this->loadVault($sender, 1));
								$sender->sendMessage("§6PrivateVaults§7>> Please run /pv again to open the Vault.");
								$this->using[strtolower($sender->getName())] = (int)$args[0];
								return true;
							}else {
								$sender->sendMessage("§6PrivateVault§7>> §cYou do not have permission to open that vault.");
								return true;
							}
						}else {
							if($args[0] < 1 || $args[0] > 50) {
								$sender->sendMessage("§6PrivateVault§7>> Usage: /pv [0-50]");
								return true;
							}else {
								if($sender->hasPermission("pv.vault." . $args[0])) {
									$sender->addWindow($this->loadVault($sender, $args[0]));
									$sender->sendTip("§aOpening Vault...");
									$this->using[strtolower($sender->getName())] = (int)$args[0];
									return true;
								}else {
									$sender->sendMessage("§6PrivateVault§7>> §cYou do not have permission to open that vault.");
									return true;
								}
							}
						}
					}else {
						$sender->sendMessage("§6PrivateVault§7>> Creating Vault...");
						for($i = 0; $i < 51; $i++) {
							$this->createVault($sender, $i);
						}
						$sender->sendMessage("§6PrivateVault§7>> Vault created, run the command again to open it!");
						return true;
					}
				}
			}
		return true;
	}
}
