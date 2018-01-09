<?php

namespace ARCore;

use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\inventory\InventoryPickupArrowEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use ARCore\ARCore;

class EventListener implements Listener {

    public function __construct(ARCore $plugin) {
        $this->plugin = $plugin;
        $this->conf = $this->plugin->getConfig();
        $this->msg = new Config($this->getDataFolder() . "message_" . $this->conf->getNested("message.lang") . ".yml");
        if($this->conf->get("Enable%Chat%Filter") == true){
            $this->filter = new ChatFilter();
        }
        if($this->conf->get("Enable%Particle") == true){
            $this->manager = new ParticleManager();
        }
    }

    public function onPlayerChat(PlayerChatEvent $event) {
        if($this->plugin->conf->get("Enable%Chat%Filter") == true){
            if (!in_array($event->getPlayer()->getDisplayName()) && !$this->filter->check($event->getPlayer(), $event->getMessage())) {
                $event->setCancelled(true);
                $event->getPlayer()->sendMessage($this->plugin->getMessage("msg", "Chat%Filter%Sorry"));
            }
        }

    }

    public function PlayerLoginEventParticles(PlayerLoginEvent $event) {
        if($this->conf->get("Enable%Particle") == true){
            if (isset($this->players[$event->getPlayer()->getDisplayName()])) {
                $this->plugin->giveParticle($event->getPlayer()->getDisplayName(), $this->players[$event->getPlayer()->getDisplayName()]);
            }
        }
    }

    public function PlayerQuitEventParticles(PlayerQuitEvent $event) {
        if($this->conf->get("Enable%Particle") == true){
            if (isset($this->players[$event->getPlayer()->getDisplayName()])) {
                $this->plugin->removeParticle($event->getPlayer()->getDisplayName());
            }   
        }
    }

    public function PlayerRespawnEventParticles(PlayerRespawnEvent $event) {
        if($this->conf->get("Enable%Particle") == true){
            if (isset($this->players[$event->getPlayer()->getDisplayName()])) {
                $this->plugin->giveParticle($event->getPlayer()->getDisplayName(), $this->players[$event->getPlayer()->getDisplayName()]);
            }
        }
    }

    public function onJoiningPlayerSettings(PlayerJoinEvent $event){ 
        $event->setJoinMessage("");
        $player = $event->getPlayer(); 
        if($this->conf->get("Enable%Set%Player%Food%Bar%On%Join") == true){
            $player->setFood($this->conf->get("Setting.Set%Player%Food%Bar%On%Join"));
        }
        if($this->conf->get("Enable%Set%Max%Player%Health%On%Join") == true){
            $player->setMaxHealth($this->conf->get("Setting.Set%Max%Player%Health%On%Join"));
        }
        if($this->conf->get("Enable%Set%Player%Health%On%Join") == true){
            $player->setHealth($this->conf->get("Setting.Set%Player%Health%On%Join"));
        }
        if($this->conf->get("Enable%Set%Player%Movement%Speed%On%Join") == true){
            $player->setMovementSpeed($this->conf->get("Setting.Set%Player%Movement%Speed%On%Join")); // 0.12 //DO NOT MESS WITH THIS!!
        }
        if($this->conf->get("Enable%Set%Player%Game$Mode%To%Survival") == true){
            if($this->conf->get("Enable%Operator%To%Bypass%Force%Gamemode") == false){
                $player->setGamemode(0);//Forgot To Set A Player Gamemode To Survival??LOL NOW YOU WONT FORGOT!!
            }else{
                return;
            }
        }
        if($this->conf->get("Enable%Hub%Spawn") == true){
            $positionx = $this->plugin->getServer()->getLevelByName($this->conf->get("Setting.Load%Default%World"))->getSafeSpawn()->getX();
            $positiony = $this->plugin->getServer()->getLevelByName($this->conf->get("Setting.Load%Default%World"))->getSafeSpawn()->getY()+1.3;
            $positionz = $this->plugin->getServer()->getLevelByName($this->conf->get("Setting.Load%Default%World"))->getSafeSpawn()->getZ();
            $worldlevel = $this->plugin->getServer()->getLevelByName($this->conf->get("Setting.Load%Default%World"));
            $player->setLevel($worldlevel);
            $player->teleport(new Vector3($positionx, $positiony, $positionz, $worldlevel));
        }
    }
 /*TEST*/
   public function disableBed(PlayerBedEnterEvent $event){
         $player = $event->getPlayer();
         if($player->getServer()->getDefaultLevel()){
         $player->sendMessage("  §8[§b§lBED§r§8] §0> §7Sorry,You Can't Slept Here Its Mine!!!");
         $event->setCancelled(true);
    }
   }
     public function onHungerChange(PlayerHungerChangeEvent $e){
         $p = $e->getPlayer();
         if($p->getServer()->getDefaultLevel()){
             $e->setCancelled();
         }
     }
     public function lol(PlayerQuitEvent $event){
         $event->setQuitMessage("");
     }
 
     public function onPlayerKick(PlayerKickEvent $e){
         $e->setQuitMessage("");
     }
 /*Plugins PRE*/
     public function PRE(PlayerRespawnEvent $event){
        $player = $event->getPlayer(); 
        $player->setFood($this->conf->get("SetPlayerFoodBarOnRespawn"));
        $player->setMaxHealth($this->conf->get("SetMaxPlayerHealthOnRespawn"));
        $player->setHealth($this->conf->get("SetPlayerHealthOnRespawn"));
        $player->setMovementSpeed(0.12);
        $player->setGamemode(0);
     }
 //This Function Will Add Percentage To Gain The Items..
 //Add Config..[DONE]
 /*Plugin dropdeath*/
   public function dropdeath(PlayerDeathEvent $event){
       $event->setDeathMessage("");
     $entity = $event->getEntity();
     $cause = $entity->getLastDamageCause();
     if($entity instanceof Player){
        if($cause instanceof Player){
         $killer->getInventory()->addItem(Item::get($this->conf->get("DropDeath")));//388
     }
   }
 }
 
     public function ByeVoidz(PlayerMoveEvent $event){
         if($event->getTo()->getFloorY() <= 7){//lucky 7
             $player = $event->getPlayer();
             $x = $this->getServer()->getDefaultLevel()->getSafeSpawn()->getX();
             $y = $this->getServer()->getDefaultLevel()->getSafeSpawn()-> getY()+1.3;
             $z = $this->getServer()->getDefaultLevel()->getSafeSpawn()->getZ();
             $level = $this->getServer()->getDefaultLevel();
             $player->setLevel($level);
             $player = $event->getPlayer();
             $player->setMaxHealth($this->conf->get("NoVoid-SetPlayerMaxHealth"));
             $player->setHealth($this->conf->get("NoVoid-SetPlayerHealth"));
             $player->setFood($this->conf->get("NoVoid-SetPlayerFood"));
             $player->setMovementSpeed(0.12);
             $player->teleport(new Vector3($x, $y, $z, $level));
             }
         }	
 
         public function PlayerKillCoins(PlayerDeathEvent $event){
             $player = $event->getEntity();
             $name = strtolower($player->getName());
      if ($player instanceof Player){
                 $cause = $player->getLastDamageCause();
         if($cause instanceof EntityDamageByEntityEvent){
                     $damager = $cause->getDamager();
                     if($damager instanceof Player){
                         $PlayerKiller = $this->conf->get("Player-Gain-Coins-PerKill");
                         $PlayerKilled = $this->conf->get("Player-Lose-Coins-PerDeath");
                         $damager->sendTip($this->conf->get("Player-Gains-Coins-For-Killing-Message"));
                         $player->sendTip($this->conf->get("Player-Lose-Coins-For-Dying-Message"));
                         $this->api->addMoney($damager, $PlayerKiller);
                         $this->api->reduceMoney($player, $PlayerKilled);
                     }
                 }
             }
         }
 
     public function NoDamageForFall(EntityDamageEvent $event){
         $entity = $event->getEntity();
         $cause = $event->getCause();
         if($entity instanceof Player && $entity->hasPermission("nofall.damage")){
             if($cause == EntityDamageEvent::CAUSE_FALL){
                 $event->setCancelled(true);
             }
         }
     }




}

?>