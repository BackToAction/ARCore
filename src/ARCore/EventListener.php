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
        $this->conf = new Config($this->getDataFolder() . "config.yml"); // change it to make sure it right.
        $this->msg = new Config($this->getDataFolder() . "message_" . $this->conf->getNested("message.lang") . ".yml");
        if($this->conf->get("Enable%Chat%Filter") == true){
            $this->filter = new ChatFilter();
        }
        if($this->conf->get("Enable%Particle") == true){
            $this->manager = new ParticleManager();
        }
        if($this->conf->get("currency.api") == "eco"){
            if($this->plugin->getServer()->getPluginManager()->getPlugin("EconomyS")){
                $this->eco = EconomyAPI::getInstance();
            }else{
                $this->currency = new CurrencyAPI(); // todo
            }
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
    public function disableBed(PlayerBedEnterEvent $event){
         $player = $event->getPlayer();
         if($this->conf->get("Disable.Use%Of%Bed") == true){
             if($player->getServer()->getLevelByName($this->conf->get("Disable.Bed%World"))){
                 $player->sendMessage($this->plugin->getMessage("msg", "Bed%Disabled%At%This%World"));
                 $event->setCancelled(true);
                }
            }
        }

    public function onHungerChange(PlayerHungerChangeEvent $event){
         $player = $event->getPlayer();
         if($this->conf->get("Disable.Use%Of%Hunger") == true){
             if($player->getServer()->getLevelByName($this->conf->get("Disable.Hunger%World"))){
                 $event->setCancelled();
                }
            }
        }
     public function lol(PlayerQuitEvent $event){
         $event->setQuitMessage("");
     }
 
     public function onPlayerKick(PlayerKickEvent $e){
         $e->setQuitMessage("");
     }
     
     public function PRE(PlayerRespawnEvent $event){
        $player = $event->getPlayer(); 
        $player->setFood($this->conf->get("Setting.Set%Player%Food%Bar%On%Respawn"));
        $player->setMaxHealth($this->conf->get("Setting.Set%Max%Player%Health%On%Respawn"));
        $player->setHealth($this->conf->get("Setting.Set%Player%Health%On%Respawn"));
        if($this->conf->get("Enable%Set%Player%Movement%Speed%On%Join") == true){
            if(0.15 > $this->conf->get("Setting.Set%Player%Movement%Speed%On%Join")){
                $player->setMovementSpeed($this->conf->get("Setting.Set%Player%Movement%Speed%On%Join"));
            }else{
                $this->plugin->getLogger()->warning($this->plugin->getMessage("msg", "Player%Speed%Must%Less%Than"));
                $player->setMovementSpeed(0.12);
            }
        }
        if($this->conf->get("Enable%Set%Player%Game$Mode%To%Survival") == true){
            if($this->conf->get("Enable%Operator%To%Bypass%Force%Gamemode") == false){
                $player->setGamemode(0);//Forgot To Set A Player Gamemode To Survival??LOL NOW YOU WONT FORGOT!!
            }else{
                return;
            }
        }
    }
    
    public function dropdeath(PlayerDeathEvent $event){
        $event->setDeathMessage("");
        $entity = $event->getEntity();
        $cause = $entity->getLastDamageCause();
        if($this->conf->get("Enable%DropDeath%Chance")){
            $chance = mt_rand(1, 555);
            if($chance == 62){
                if($entity instanceof Player){
                    if($cause instanceof Player){
                        $cause->sendMessage($this->plugin->getMessage("msg", "Drop%Message"));
                        $cause->getInventory()->addItem(Item::get($this->conf->get("Setting.Drop%Death%Chance%Item")));//388
                    }
                }
            }
        }
    }
 
     public function ByeVoidz(PlayerMoveEvent $event){
         if($this->conf->get("Enable%No%Void") == true){
             if($event->getTo()->getFloorY() <= $this->conf->get("NoVoid.T-NoVoid")){
                 $player = $event->getPlayer();
                 $x = $this->getServer()->getLevelByName($this->conf->get("NoVoid.NoVoid%World"))->getSafeSpawn()->getX();
                 $y = $this->getServer()->getLevelByName($this->conf->get("NoVoid.NoVoid%World"))->getSafeSpawn()-> getY()+1.3;
                 $z = $this->getServer()->getLevelByName($this->conf->get("NoVoid.NoVoid%World"))->getSafeSpawn()->getZ();
                 $level = $player->getServer()->getLevelByName($this->conf->get("NoVoid.NoVoid%World"));
                 $player->setLevel($level);
                 $player->telepoty(new Vector3($x, $y, $z, $level));
                }
            }
        }
 
         public function GetCoinsFromPK(PlayerDeathEvent $event){
             if($this->conf->get("Enable%Gain%Coins%From%PK") == true){
                 $victim = $event->getEntity(); // A User(Player) From Minecraft Is Also An Entity. Thus Let Say That This Entity Is User(Player) // From Player POV
                 $victim_ign = strtolower($player->getName()); // might get remove by me( HyGlobalHD )
                 if($victim instanceof Player){// Now, To Make Sure The Entity Is A Player So When Hit Other Entity Nothing Happen, ( Hopefully ) 
                    $cause = $victim->getLastDamageCause(); // Who The Last Cause That Beat The User(Player) To Death.
                    if($cause instanceof EntityDamageByEntityEvent){
                        $culprit = $cause->getDamager(); // The One Who Kill The User(Player)
                        if($culprit instanceof Player){// To Make Sure The Culprit Is Also A Player
                            $gains = $this->conf->get("Setting.Gain%Coins%From%PK"); // get config \O/
                            $losts = $this->conf->get("Setting.Lost%Coins%From%Pked");
                            if($this->conf->get("currency.api") == "eco"){
                                $this->eco->addMoney($culprit, $gains);
                                $this->eco->reduceMoney($victim, $losts)
                                // No Need Message Or Popup // Note: To Encourage Use Of Hud. So Enable IT!!
                            }
                            elseif($this->conf->get("currency.api") == "arcc"){
                                $this->currency->addCoins($culprit, $gains);
                                $this->currency->reduceCoins($victim, $losts);
                            }
                        }
                    }
                }
             }
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