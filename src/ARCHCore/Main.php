<?php

namespace ARCHCore;
/*/
 *  Author: GamerXzavier / HyGlobalHD
 *  
 *  This Project Are From ArchRPG.
 *  Any Contribute Are Allow As Long This Text Here.
 *  
 *  #Write Ur Name If U Contribute.
 *  Contribute: [NeuroBinds]
 *  
 *  © Copyright Of NeuroBinds Project Corps.
 * 
 *  Website: https://github.com/ArchRPG
 *
 *
/*/

//player
use pocketmine\Player;
//inventory
use pocketmine\inventory\Inventory;
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\DoubleChestInventory;
//events
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
//items
use pocketmine\item\Slimeball;
use pocketmine\item\Item;
//commands
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
//utils
use pocketmine\utils\TextFormat;
use pocketmine\utils\config;
use pocketmine\utils\TextFormat as Color;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\TextFormat as MT;
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\BinaryStream;
use pocketmine\utils\Binary;
//entity
use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
//level
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\level\sound\GhastShootSound;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\BubbleParticle;
use pocketmine\level\particle\Particle;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\Position;
use pocketmine\level\Location;
use pocketmine\level\Position\getLevel;
use pocketmine\level\format\Chunk;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
//block
use pocketmine\block\IronOre;
use pocketmine\block\GoldOre;
use pocketmine\block\Block;
//plugin
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginManager;
use pocketmine\plugin\Plugin;
//server
use pocketmine\Server;
//network
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\network\protocol\PlayerActionPacket;
use pocketmine\network\protocol\MovePlayerPacket;
use pocketmine\network\protocol\BlockEventPacket;
//math
use pocketmine\math\Vector3;
use pocketmine\math\Math;
use pocketmine\math\AxisAlignedBB;
//scheduler
use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\CallbackTask;
//nbt
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\CompoundTag;
//permission
use pocketmine\permission\Permission;
//others
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener{
  
  public $prefix = TextFormat::GRAY."[ ".TextFormat::YELLOW."ARCHCore".TextFormat::GRAY." ]".TextFormat::WHITE." ";

public function onEnable(){
  $this->getServer()->getPluginManager()->registerEvents($this, $this);
  @mkdir($this->getDataFolder());
	@mkdir($this->getDataFolder()."Players");
  $this->getLogger()->info($this->prefix ."has been successfully enabled.");
  $this->getLogger()->notice(TextFormat::AQUA ."
        *
        *
        * @Author: HyGlobalHD
        * @Github: https://github.com/hyglobalhd/
        *
        * Copyright (C) 2016 ARCHCore
        *
        * This program is free software: you can redistribute it and/or modify
        * it under the terms of the GNU General Public License as published by
        * the Free Software Foundation, either version 3 of the License, or
        * (at your option) any later version.
        *
        * This program is distributed in the hope that it will be useful,
        * but WITHOUT ANY WARRANTY; without even the implied warranty of
        * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        * GNU General Public License for more details.
        *
        * You should have received a copy of the GNU General Public License
        * along with this program.  If not, see <http://www.gnu.org/licenses/>.
        *
        *");
        
        public function onJoin(PlayerJoinEvent $event){
          $player = $event->getPlayer();
          $name = $player->getName();
          @mkdir($this->getDataFolder()."Players/". $player->getName());
          $playerfile = new Config($this->getDataFolder()."Players/". $player->getName() ."/". $player->getName() .".yml", Config::YAML);
          // else
					$playerfile->save();
          $player->sendMessage($this->prefix."");
        }
        
        public function onFight(EntityDamageEvent $event) {
          if($event instanceof EntityDamageByEntityEvent && $event->getDamager() instanceof Player) {
            $hit = $event->getEntity();
            $damager = $event->getDamager();
            $level = $p->getLevel();
            $cfglevel = $this->config->get("main.level");
            if($damager->getLevel() == $this->getServer()->getLevelByName($cfglevel)) {
              if($p->hasPermission("arch.class.assassin")) {
                if($damager->getItemInHand()->getId() == 359) { // change this to whatever weapon you'd like
                    $x = $hit->x;
                    $y = $hit->y;
                    $z = $hit->z;
                    $hitpos = $hit->getPosition(new Vector3($x, $y, $z));
                    $level->addParticle(new CriticalParticle($hitpos));
                    $this->setDamage(4);
                    $crash = new DoorCrashSound($hitpos);
                    $hitpos->getLevel()->addSound($crash);
                    if($p->hasPermission("arch.class.mage")) {
                      if($damager->getItemInHand()->getId() == 280) {
                        $r = mt_rand();
                        $g = mt_rand();
                        $b = mt_rand();
                        
                        $x = $hit->x;
                        $y = $hit->y;
                        $z = $hit->z;
                        
                        $a = 1;
                        $radius = 0.5;
                        $count = 250;
                        
                        $hitpos = $hit->getPosition(new Vector3($x, $y, $z));
                        $warp = new EndermanTeleportSound($hitpos);
                        
                        $particle = new DustParticle($c, $r, $g, $b, $a);
				              	$particle->setComponents($hitpos);
				              	$hitpos->addParticle($particle);
                        
                        $hitpos->getLevel()->addSound($warp);
                        $this->setKnockBack(1);
                        $hit->setOnFire(4);
                        $this->setDamage(4);
                        if($p->hasPermission("arch.class.archer")) {
                          if($damager->getItemInHand()->getId() == 272) {
                            $x = $hit->x;
                            $y = $hit->y;
                            $z = $hit->z;
                            $hitpos = $hit->getPosition(new Vector3($x, $y, $z));
                            $cling = new AnvilFallSound($hitpos);
                            $hitpos->getLevel()->addSound($cling);
                            $this->setKnockBack(3);
                            $this->setDamage(3);
                          }
                            $projectile = $event->getProjectile();
                            if($event->isCancelled()){
                              $projectile->kill();
                              
                            }else if($projectile instanceof Projectile){
                              $this->server->getPluginManager()->callEvent($launch = new ProjectileLaunchEvent($projectile));
                              if($launch->isCancelled()){
                                $projectile->kill();
                                
                              }else{
                                $projectile->spawnToAll();
                                $projectile->setOnFire(100);
                                if($hit instanceof Player && $event->getCause() === EntityDamageEvent::CAUSE_PROJECTILE){
                                  $event->setDamage(6);
                                  $event->setKnockBack($event->getKnockBack() * 2);
                                  if($p->hasPermission("arch.class.warrior")) {
                                    if($damager->getItemInHand()->getId() == 267) {
                                      $x = $hit->x;
                                      $y = $hit->y;
                                      $z = $hit->z;
                                      $hitpos = $hit->getPosition(new Vector3($x, $y, $z));
                                      $level->addParticle(new CriticalParticle($hitpos));
                                      $hitpos->getLevel()->addSound($crash);
                                      $this->setKnockBack(2);
                                      $this->setDamage(6);
                                    }
                                  }
                                }
                              }
                            }
                        }
                      }
                    }
                }
              }
            }
          }
        }
}
        
        // I'm going to test this now
