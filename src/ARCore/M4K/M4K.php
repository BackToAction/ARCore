<?php

namespace ARCore\M4K;

use pocketmine\utils\TextFormat as MT;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\server;
use pocketmine\level;
use pocketmine\item\Item;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\event\player\PlayerDeathEvent;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;

	class Main extends PluginBase implements Listener
	
	{
		public function onEnable()
		{
			$this->getServer()->getPluginManager()->registerEvents($this,$this);
			$this->getLogger()->info(MT::AQUA."M4K Registered!");
			$this->api = EconomyAPI::getInstance();
			
			if (!file_exists($this->getDataFolder()))
			{
				@mkdir($this->getDataFolder(), true);
			}
			$this->config = new Config($this->getDataFolder(). "config.yml", Config::YAML, array("LMOAKiller" => 10,"LMOALoser"=> 10));	
		}
	
		public function onPlayerDeathEvent(PlayerDeathEvent $event)
		{
			$player = $event->getEntity();
			$name = strtolower($player->getName());
		
			if ($player instanceof Player)
			{
				$cause = $player->getLastDamageCause();
		
				if($cause instanceof EntityDamageByEntityEvent)
				{
					$damager = $cause->getDamager();
					
					if($damager instanceof Player)
					{
						$LMOAKiller = $this->config->get("LMOAKiller");
						$LMOALoser = $this->config->get("LMOALoser");
						$damager->sendPopup(MT::GOLD."You get $LMOAKiller Coins For Killing!");
						$player->sendPopup(MT::GOLD."You lose $LMOALoser Coins For Dying!");
						$this->api->addMoney($damager, $LMOAKiller);
						$this->api->reduceMoney($player, $LMOALoser);
					}
				}
			}
		}
		
		public function onDisable()
		{
			$this->getLogger()->info(MT::AQUA."Plugin unloaded!");
		}
	}
