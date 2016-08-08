<?php

namespace FactionsPro;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\PluginTask;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerDeathEvent;
class FactionListener implements Listener {
	
	public $plugin;
	
	public function __construct(FactionMain $pg) {
		$this->plugin = $pg;
	}
	
	public function factionChat(PlayerChatEvent $PCE) {
		
		$player = strtolower($PCE->getPlayer()->getName());
		//MOTD Check
		//TODO Use arrays instead of database for faster chatting?
		
		if($this->plugin->motdWaiting($player)) {
			if(time() - $this->plugin->getMOTDTime($player) > 30) {
				$PCE->getPlayer()->sendMessage($this->plugin->formatMessage("Timed out. Please use /c motd again."));
				$this->plugin->db->query("DELETE FROM motdrcv WHERE player='$player';");
				$PCE->setCancelled(true);
				return true;
			} else {
				$motd = $PCE->getMessage();
				$faction = $this->plugin->getPlayerFaction($player);
				$this->plugin->setMOTD($faction, $player, $motd);
				$PCE->setCancelled(true);
				$PCE->getPlayer()->sendMessage($this->plugin->formatMessage("§6- §aSuccessfully updated Clan message of the day!", true));
			}
			return true;
		}
		
		//Member
		if($this->plugin->isInFaction($PCE->getPlayer()->getName()) && $this->plugin->isMember($PCE->getPlayer()->getName())) {
			$message = $PCE->getMessage();
			$player = $PCE->getPlayer()->getName();
			$faction = $this->plugin->getPlayerFaction($player);
			$number_of_players = $this->plugin->getNumberOfPlayers($faction);

			$PCE->setFormat("[$faction] $player: $message");
			return true;
		}
		//Officer
		elseif($this->plugin->isInFaction($PCE->getPlayer()->getName()) && $this->plugin->isOfficer($PCE->getPlayer()->getName())) {
			$message = $PCE->getMessage();
			$player = $PCE->getPlayer()->getName();
			$faction = $this->plugin->getPlayerFaction($player);
			$faction = $this->plugin->getPlayerFaction($player);
			$number_of_players = $this->plugin->getNumberOfPlayers($faction);

			$PCE->setFormat("*[$faction] $player: $message");
			return true;
		}
		//Leader
		elseif($this->plugin->isInFaction($PCE->getPlayer()->getName()) && $this->plugin->isLeader($PCE->getPlayer()->getName())) {
			$message = $PCE->getMessage();
			$player = $PCE->getPlayer()->getName();
			$faction = $this->plugin->getPlayerFaction($player);
			$faction = $this->plugin->getPlayerFaction($player);
			$number_of_players = $this->plugin->getNumberOfPlayers($faction);

			$PCE->setFormat("**[$faction] $player: $message");
			return true;
		//Not in faction
		}else {
			$message = $PCE->getMessage();
			$player = $PCE->getPlayer()->getName();
			$PCE->setFormat("$player: $message");
		}
	}
	
	public function factionPVP(EntityDamageEvent $factionDamage) {
		if($factionDamage instanceof EntityDamageByEntityEvent) {
			if(!($factionDamage->getEntity() instanceof Player) or !($factionDamage->getDamager() instanceof Player)) {
				return true;
			}
			if(($this->plugin->isInFaction($factionDamage->getEntity()->getPlayer()->getName()) == false) or ($this->plugin->isInFaction($factionDamage->getDamager()->getPlayer()->getName()) == false)) {
				return true;
			}
			if(($factionDamage->getEntity() instanceof Player) and ($factionDamage->getDamager() instanceof Player)) {
				$player1 = $factionDamage->getEntity()->getPlayer()->getName();
				$player2 = $factionDamage->getDamager()->getPlayer()->getName();
                $f1 = $this->plugin->getPlayerFaction($player1);
                $f2 = $this->plugin->getPlayerFaction($player2);
				if($this->plugin->sameFaction($player1, $player2) == true or $this->plugin->areAllies($f1,$f2)) {
					$factionDamage->setCancelled(true);
				}
			}
		}
	}
	public function factionBlockBreakProtect(BlockBreakEvent $event) {
		if($this->plugin->isInPlot($event->getPlayer())) {
			if($this->plugin->inOwnPlot($event->getPlayer())) {
				return true;
			} else {
				$event->setCancelled(true);
				$event->getPlayer()->sendMessage($this->plugin->formatMessage("§6- §cYou cannot break blocks here. This is already a property of a Clan. Type /c pos for details."));
				return true;
			}
		}
	}
	
	public function factionBlockPlaceProtect(BlockPlaceEvent $event) {
		if($this->plugin->isInPlot($event->getPlayer())) {
			if($this->plugin->inOwnPlot($event->getPlayer())) {
				return true;
			} else {
				$event->setCancelled(true);
				$event->getPlayer()->sendMessage($this->plugin->formatMessage("§6- §cYou cannot place blocks here. This is already a property of a Clan. Type /c pos for details."));
				return true;
			}
		}
	}
	public function onKill(PlayerDeathEvent $event){
        $cause = $event->getEntity()->getLastDamageCause();
        if($cause instanceof EntityDamageByEntityEvent){
            $killer = $cause->getDamager();
            if($killer instanceof Player){
                $p = strtoupper($killer->getPlayer()->getName());
                if($this->plugin->isInFaction($p)){
                    $f = $this->plugin->getPlayerFaction($p);
                    $e = $this->plugin->prefs->get("PowerGainedPerKillingAnEnemy");
                    $this->plugin->addFactionPower($f,$e);
                }
            }
        }
    }
    public function onDeath(PlayerDeathEvent $event) {
		$victim = $event->getPlayer();
		$cause = $victim->getLastDamageCause();
		if($cause instanceof EntityDamageByEntityEvent) {
			$killer = $cause->getDamager();
			if($victim instanceof Player) {
				$q = strtoupper($victim->getPlayer()->getName());
                if($this->plugin->isInFaction($q)){
                    $a = $this->plugin->getPlayerFaction($q);
                    $b = $this->plugin->prefs->get("PowerReducedPerDeathByAnEnemy");
                    $this->plugin->subtractFactionPower($a,$b);
                }
				
			}
		} 
	}
	}
