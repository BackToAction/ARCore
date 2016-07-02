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
 *  Not For Sale.
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
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerDeathEvent;
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

    public function dataPath(){

      return $this->getDataFolder();

    }
/*Plugins OnEnable*/
   public function onEnable(){
		$this->makeSaveFiles();
    $this->getServer()->getScheduler()->scheduleRepeatingTask(new ArchParticles($this), 10);
//load level/world
        $this->getServer()->loadLevel("ARCHRPG_LOBBY");
/*Server Name*/
		$this->getServer()->getNetwork()->setName(TextFormat::GREEN . "Arch" . TextFormat::GOLD . "RPG " . TextFormat::RED . "Online" . TextFormat::WHITE . "\n" . TextFormat::BLACK . "Beta§l§8»v0.1 ");
       $this->getServer()->getPluginManager()->registerEvents($this ,$this);
       $this->getLogger()->info("\n\n§e==========\n§aRegister\n§bArchRPG§eCore\n§e==========\n\n");
/*/====Using EconomyAPI====/*/
			$this->api = EconomyAPI::getInstance();
/*-=-=-=-Config-=-=-=-*/
			if (!file_exists($this->getDataFolder())){
				@mkdir($this->getDataFolder(), true);
		  @mkdir($this->getDataFolder());
			}
//congfig file
			$this->config = new Config($this->getDataFolder(). "DisableCommands.yml", Config::YAML, array("Commands" => [], "Permissions" => true));
			
			$this->commands = $this->config->get("Commands");
			$this->permissions = $this->config->get('Permissions');

			$this->config = new Config($this->getDataFolder(). "ArchM4K.yml", Config::YAML, array("Coins_Gain_Per_Kills" => 10,"Coins_Lost_Per_Deaths"=> 10));

    @mkdir($this->getServer()->getDataPath() . "/plugins/ArchCoreSystem/");
    $this->EffectKatana = (new Config($this->getDataFolder()."EffectKatana.yml", Config::YAML, array("effects" => array(276 => 2),"effect-level" => 5, "effect-time" => 10, "particles-visible" => false)));


      @mkdir($this->dataPath());

      $this->cfg = new Config($this->dataPath() . "newspaper.yml", Config::YAML, array("page_1" => array("message_1" => "first message here", "message_2" => "second message here", "message_3" => "third message here", "message_4" => "fourth message here", "message_5" => "fifth message here", "message_6" => "sixth message here", "message_7" => "seventh message here", "message_8" => "eighth message here", "message_9" => "ninth message here", "message_10" => "tenth message"), "page_2" => array("message_1" => "first message here", "message_2" => "second message here", "message_3" => "third message here", "message_4" => "fourth message here", "message_5" => "fifth message here", "message_6" => "sixth message here", "message_7" => "seventh message here", "message_8" => "eighth message here", "message_9" => "ninth message here", "message_10" => "tenth message"), "page_3" => array("message_1" => "first message here", "message_2" => "second message here", "message_3" => "third message here", "message_4" => "fourth message here", "message_5" => "fifth message here", "message_6" => "sixth message here", "message_7" => "seventh message here", "message_8" => "eighth message here", "message_9" => "ninth message here", "message_10" => "tenth message"), "page_4" => array("message_1" => "first message here", "message_2" => "second message here", "message_3" => "third message here", "message_4" => "fourth message here", "message_5" => "fifth message here", "message_6" => "sixth message here", "message_7" => "seventh message here", "message_8" => "eighth message here", "message_9" => "ninth message here", "message_10" => "tenth message"), "page_5" => array("message_1" => "first message here", "message_2" => "second message here", "message_3" => "third message here", "message_4" => "fourth message here", "message_5" => "fifth message here", "message_6" => "sixth message here", "message_7" => "seventh message here", "message_8" => "eighth message here", "message_9" => "ninth message here", "message_10" => "tenth message"), "page_6" => array("message_1" => "first message here", "message_2" => "second message here", "message_3" => "third message here", "message_4" => "fourth message here", "message_5" => "fifth message here", "message_6" => "sixth message here", "message_7" => "seventh message here", "message_8" => "eighth message here", "message_9" => "ninth message here", "message_10" => "tenth message"), "page_7" => array("message_1" => "first message here", "message_2" => "second message here", "message_3" => "third message here", "message_4" => "fourth message here", "message_5" => "fifth message here", "message_6" => "sixth message here", "message_7" => "seventh message here", "message_8" => "eighth message here", "message_9" => "ninth message here", "message_10" => "tenth message"), "page_8" => array("message_1" => "first message here", "message_2" => "second message here", "message_3" => "third message here", "message_4" => "fourth message here", "message_5" => "fifth message here", "message_6" => "sixth message here", "message_7" => "seventh message here", "message_8" => "eighth message here", "message_9" => "ninth message here", "message_10" => "tenth message"), "page_9" => array("message_1" => "first message here", "message_2" => "second message here", "message_3" => "third message here", "message_4" => "fourth message here", "message_5" => "fifth message here", "message_6" => "sixth message here", "message_7" => "seventh message here", "message_8" => "eighth message here", "message_9" => "ninth message here", "message_10" => "tenth message"), "page_10" => array("message_1" => "first message here", "message_2" => "second message here", "message_3" => "third message here", "message_4" => "fourth message here", "message_5" => "fifth message here", "message_6" => "sixth message here", "message_7" => "seventh message here", "message_8" => "eighth message here", "message_9" => "ninth message here", "message_10" => "tenth message")));

    }
/*Plugin MakeSaveFiles*/
	private function makeSaveFiles(){
		$this->saveResource("config.yml");
		$this->getConfig()->save();
	}
/*Plugins OnDisable*/
   public function onDisable(){
       $this->getLogger()->info("\n\n§cServer Shutting Down!\n§cServer Shutting Down!\n§cServer Shutting Down!\n§cServer Shutting Down!\n§cServer Shutting Down!\n\n");
   }
/*Plugin AddParticle*/
   public function addParticle(PlayerJoinEvent $event){ 
       $level = $this->getServer()->getLevelByName("ARCHRPG_LOBBY");
       $player = $event->getPlayer();
       $level->addParticle(new FloatingTextParticle(new Vector3(1.55, 17.20, 6.55),"", "§aWelcome Beginner!\n§eYou Will Randomly Teleport\n§eTo Random City When You Tap The Doors!"));
   }
/*Plugin OnJoin*/
   public function onJoin(PlayerJoinEvent $event){ 
       $player = $event->getPlayer(); 
       $player->setFood(200);
       $player->setHealth(200);
   }
/*Plugins PRE*/
    public function PRE(PlayerRespawnEvent $event){
   $event->getPlayer()->setMaxHealth(200);
   $event->getPlayer()->setHealth(200);       
    }
/*Plugins sendcmd*/
   public function sendcmd(PlayerCommandPreprocessEvent $event) {
       $cmd3 = explode(" ", strtolower($event->getMessage()));
       $player = $event->getPlayer();
   if($cmd3[0] === "/?"){ 
       $player->sendMessage("§b§l»§r§eHelp Commands Available§l§b«");
       $player->sendMessage("");
       $player->sendMessage("");
       $player->sendMessage("");
       $player->sendMessage("");
       $event->setCancelled();
        }
    }
/*Plugin sendHelp*/
   public function sendHelp(PlayerCommandPreprocessEvent $event) {
       $cmd4 = explode(" ", strtolower($event->getMessage()));
       $player = $event->getPlayer();
   if($cmd4[0] === "/help"){ 
       $player->sendMessage("§b§l»§r§eHelp Commands Available§l§b«");
       $player->sendMessage("");
       $player->sendMessage("");
       $player->sendMessage("");
       $player->sendMessage("");
       $event->setCancelled();
        }
    }
/*Plugins OnHit*/
    public function onHit(EntityDamageEvent $event){
        if($event instanceof EntityDamageByChildEntityEvent){
            $target = $event->getEntity();
            $player = $event->getDamager();
            $event->getDamager()->sendPopup("§aShot A Player!");
            $player->getLevel()->addSound(new AnvilFallSound($player), [$player]);
                }
        }
/*Plugin OnPlayerDeathEvent*/
		public function onPlayerDeathEvent(PlayerDeathEvent $event){
			$player = $event->getEntity();
			$name = strtolower($player->getName());
		
			if ($player instanceof Player){
				$cause = $player->getLastDamageCause();
		
				if($cause instanceof EntityDamageByEntityEvent){
					$damager = $cause->getDamager();
					
					if($damager instanceof Player){
						$CGPK = $this->config->get("Coins_Gain_Per_Kills");
						$CLPK = $this->config->get("Coins_Lost_Per_Deaths");
						$damager->sendPopup(MT::GOLD."You get $CGPK Coins For Killing!");
						$player->sendPopup(MT::GOLD."You lose $CLPK Coins For Dying!");
						$this->api->addMoney($damager, $CGPK);
						$this->api->reduceMoney($player, $CLPK);
					}
				}
			}
		}
/*Plugin OpGoldenApples*/
public function OpGoldenApple(PlayerItemConsumeEvent $event){

   $player=$event->getPlayer();

   if($event->getItem()->getId() === 322){

             $player->addEffect(Effect::getEffect(10)->setAmplifier(3)->setDuration(200)->setVisible(true));
             $player->addEffect(Effect::getEffect(21)->setAmplifier(1)->setDuration(1000)->setVisible(true));
             $player->setHealth($player->getHealth() + 6);

    }
 }
/*Plugins HitByAProjectileWeapon*/
    public function HitByAProjectileWeapon(EntityDamageEvent $event){
        if($event instanceof EntityDamageByChildEntityEvent){
            $target = $event->getEntity();
            $player = $event->getDamager();
            $event->getDamager()->sendPopup("§aShot A Player!");
            $player->getLevel()->addSound(new AnvilFallSound($player), [$player]);
                }
        }
/*Plugin NoFallDamager*/
	public function NoFallDamager(EntityDamageEvent $event){
		$entity = $event->getEntity();
		$cause = $event->getCause();
		if($entity instanceof Player && $entity->hasPermission("N.F.D")){
			if($cause == EntityDamageEvent::CAUSE_FALL){
				$event->setCancelled(true);
			}
		}
	}
/*Plugin dropdeath*/
  public function dropdeath(PlayerDeathEvent $event){
    $entity = $event->getEntity();
    $cause = $entity->getLastDamageCause();
    if($entity instanceof Player){
       if($cause instanceof Player){
        $killer->getInventory()->addItem(Item::get(388,0,1));
    }
  }
}
/*Plugins OnInteract*/
  public function onInteract(PlayerInteractEvent $event){
    $block = $event->getBlock();
    $player = $event->getPlayer();
    $inventory = $player->getInventory();       
      if($block->getId() === Block::CHEST){     
        if($inventory->contains(new Slimeball(0,1))) {
        $event->setCancelled();
        $player->sendMessage(C::GREEN . "Congratulations You Opened GachaBox!");
        $player->sendMessage(C::AQUA . C::BOLD . "§bPlease Check Your Inventory!");
        $level = $player->getLevel();
        $x = $block->getX();
        $y = $block->getY();
        $z = $block->getZ();
        $r = 0;
        $g = 255;
        $b = 255;
        $center = new Vector3($x+1, $y, $z);
        $radius = 0.5;
        $count = 100;
        $particle = new DustParticle($center, $r, $g, $b, 1);
          for($yaw = 0, $y = $center->y; $y < $center->y + 4; $yaw += (M_PI * 2) / 20, $y += 1 / 20){
              $x = -sin($yaw) + $center->x;
              $z = cos($yaw) + $center->z;
              $particle->setComponents($x, $y, $z);
              $level->addParticle($particle);
}
        $prize = rand(1,6);
        switch($prize){
        case 1:
          $inventory->addItem(Item::get(293,0,1));
        break;
        case 2:
          $inventory->addItem(Item::get(293,0,1));
        break;   
        case 3:
          $inventory->addItem(Item::get(293,0,1));
        break;   
        case 4:
          $inventory->addItem(Item::get(293,0,1));
        break;      
        case 5:
          $inventory->addItem(Item::get(293,0,1));
        break;     
        case 6:
          $inventory->addItem(Item::get(293,0,1));  
        break;
    }
  }
}
}
/*Plugin OnPlayerCommandzzz*/
		public function onPlayerCommandzzz(PlayerCommandPreprocessEvent $event){
			$message = $event->getMessage();
			$name = strtolower($event->getPLayer()->getName());
			if($message === "/" && !$event->getPlayer()->isOP()){ 
				$command = substr($message, 1);
				$args = explode(" ", $command);

				foreach($this->commands as $command)
				{
					if(strtolower($args[0]) === "$command")
					{
						$event->getPlayer()->sendMessage(MT::RED."Eiyy LMOA dats commands huh?\n§cNot Available >.<'");
						$event->setCancelled(true);
					}
				}
			}
		}
/*Plugin OnBreakzz*/
	public function onBreakzz(BlockBreakEvent $event){
		if($event->isCancelled()){
			$p = $event->getPlayer();
			if(isset($this->positions[$p->getName()])){
				$pos = $this->positions[$p->getName()];
				$this->revert($p,$pos,$pos->yaw,$pos->pitch);
			}
		}
	}
/*Plugin Revert*/
	public function revert($player, $pos, $yaw = null, $pitch = null, $mode = 0){
		$pk = new MovePlayerPacket();
		$pk->x = $pos->x;
		$pk->y = $pos->y + $player->getEyeHeight();
		$pk->z = $pos->z;
		$pk->bodyYaw = $yaw;
		$pk->pitch = $pitch;
		$pk->yaw = $yaw;
		$pk->mode = $mode;
		$pk->eid = 0;
		$player->dataPacket($pk);
	}
/*Plugin SendNewszzz*/
    public function sendNewszzz(PlayerCommandPreprocessEvent $event) {

      $command = explode(" ", strtolower($event->getMessage()));

      $player = $event->getPlayer();

      if($command[0] === "/news" or $command[0] === "/newspaper") {

          $page_one_messages = $this->cfg->get("page_1");

          $player->sendMessage($page_one_messages["message_1"]);

          $player->sendMessage($page_one_messages["message_2"]);

          $player->sendMessage($page_one_messages["message_3"]);

          $player->sendMessage($page_one_messages["message_4"]);

          $player->sendMessage($page_one_messages["message_5"]);

          $player->sendMessage($page_one_messages["message_6"]);

          $player->sendMessage($page_one_messages["message_7"]);

          $player->sendMessage($page_one_messages["message_8"]);

          $player->sendMessage($page_one_messages["message_9"]);

          $player->sendMessage($page_one_messages["message_10"]);

          $event->setCancelled();

        if(isset($command[1])) {

        if($command[1] === "1" or $command[1] === "one") {

          $page_one_messages = $this->cfg->get("page_1");

          $player->sendMessage($page_one_messages["message_1"]);

          $player->sendMessage($page_one_messages["message_2"]);

          $player->sendMessage($page_one_messages["message_3"]);

          $player->sendMessage($page_one_messages["message_4"]);

          $player->sendMessage($page_one_messages["message_5"]);

          $player->sendMessage($page_one_messages["message_6"]);

          $player->sendMessage($page_one_messages["message_7"]);

          $player->sendMessage($page_one_messages["message_8"]);

          $player->sendMessage($page_one_messages["message_9"]);

          $player->sendMessage($page_one_messages["message_10"]);

        } else if($command[1] === "2" or $command[1] === "two") {

          $page_two_messages = $this->cfg->get("page_2");

          $player->sendMessage($page_two_messages["message_1"]);

          $player->sendMessage($page_two_messages["message_2"]);

          $player->sendMessage($page_two_messages["message_3"]);

          $player->sendMessage($page_two_messages["message_4"]);

          $player->sendMessage($page_two_messages["message_5"]);

          $player->sendMessage($page_two_messages["message_6"]);

          $player->sendMessage($page_two_messages["message_7"]);

          $player->sendMessage($page_two_messages["message_8"]);

          $player->sendMessage($page_two_messages["message_9"]);

          $player->sendMessage($page_two_messages["message_10"]);

        } else if($command[1] === "3" or $command[1] === "three") {

          $page_three_messages = $this->cfg->get("page_3");

          $player->sendMessage($page_three_messages["message_1"]);

          $player->sendMessage($page_three_messages["message_2"]);

          $player->sendMessage($page_three_messages["message_3"]);

          $player->sendMessage($page_three_messages["message_4"]);

          $player->sendMessage($page_three_messages["message_5"]);

          $player->sendMessage($page_three_messages["message_6"]);

          $player->sendMessage($page_three_messages["message_7"]);

          $player->sendMessage($page_three_messages["message_8"]);

          $player->sendMessage($page_three_messages["message_9"]);

          $player->sendMessage($page_three_messages["message_10"]);

        } else if($command[1] === "4" or $command[1] === "four") {

          $page_four_messages = $this->cfg->get("page_4");

          $player->sendMessage($page_four_messages["message_1"]);

          $player->sendMessage($page_four_messages["message_2"]);

          $player->sendMessage($page_four_messages["message_3"]);

          $player->sendMessage($page_four_messages["message_4"]);

          $player->sendMessage($page_four_messages["message_5"]);

          $player->sendMessage($page_four_messages["message_6"]);

          $player->sendMessage($page_four_messages["message_7"]);

          $player->sendMessage($page_four_messages["message_8"]);

          $player->sendMessage($page_four_messages["message_9"]);

          $player->sendMessage($page_four_messages["message_10"]);

        } else if($command[1] === "5" or $command[1] === "five") {

          $page_five_messages = $this->cfg->get("page_5");

          $player->sendMessage($page_five_messages["message_1"]);

          $player->sendMessage($page_five_messages["message_2"]);

          $player->sendMessage($page_five_messages["message_3"]);

          $player->sendMessage($page_five_messages["message_4"]);

          $player->sendMessage($page_five_messages["message_5"]);

          $player->sendMessage($page_five_messages["message_6"]);

          $player->sendMessage($page_five_messages["message_7"]);

          $player->sendMessage($page_five_messages["message_8"]);

          $player->sendMessage($page_five_messages["message_9"]);

          $player->sendMessage($page_five_messages["message_10"]);

        } else if($command[1] === "6" or $command[1] === "six") {

          $page_six_messages = $this->cfg->get("page_6");

          $player->sendMessage($page_six_messages["message_1"]);

          $player->sendMessage($page_six_messages["message_2"]);

          $player->sendMessage($page_six_messages["message_3"]);

          $player->sendMessage($page_six_messages["message_4"]);

          $player->sendMessage($page_six_messages["message_5"]);

          $player->sendMessage($page_six_messages["message_6"]);

          $player->sendMessage($page_six_messages["message_7"]);

          $player->sendMessage($page_six_messages["message_8"]);

          $player->sendMessage($page_six_messages["message_9"]);

          $player->sendMessage($page_six_messages["message_10"]);

        } else if($command[1] === "7" or $command[1] === "seven") {

          $page_seven_messages = $this->cfg->get("page_7");

          $player->sendMessage($page_seven_messages["message_1"]);

          $player->sendMessage($page_seven_messages["message_2"]);

          $player->sendMessage($page_seven_messages["message_3"]);

          $player->sendMessage($page_seven_messages["message_4"]);

          $player->sendMessage($page_seven_messages["message_5"]);

          $player->sendMessage($page_seven_messages["message_6"]);

          $player->sendMessage($page_seven_messages["message_7"]);

          $player->sendMessage($page_seven_messages["message_8"]);

          $player->sendMessage($page_seven_messages["message_9"]);

          $player->sendMessage($page_seven_messages["message_10"]);

        } else if($command[1] === "8" or $command[1] === "eight") {

          $page_eight_messages = $this->cfg->get("page_8");

          $player->sendMessage($page_eight_messages["message_1"]);

          $player->sendMessage($page_eight_messages["message_2"]);

          $player->sendMessage($page_eight_messages["message_3"]);

          $player->sendMessage($page_eight_messages["message_4"]);

          $player->sendMessage($page_eight_messages["message_5"]);

          $player->sendMessage($page_eight_messages["message_6"]);

          $player->sendMessage($page_eight_messages["message_7"]);

          $player->sendMessage($page_eight_messages["message_8"]);

          $player->sendMessage($page_eight_messages["message_9"]);

          $player->sendMessage($page_eight_messages["message_10"]);

        } else if($command[1] === "9" or $command[1] === "nine") {

          $page_nine_messages = $this->cfg->get("page_9");

          $player->sendMessage($page_nine_messages["message_1"]);

          $player->sendMessage($page_nine_messages["message_2"]);

          $player->sendMessage($page_nine_messages["message_3"]);

          $player->sendMessage($page_nine_messages["message_4"]);

          $player->sendMessage($page_nine_messages["message_5"]);

          $player->sendMessage($page_nine_messages["message_6"]);

          $player->sendMessage($page_nine_messages["message_7"]);

          $player->sendMessage($page_nine_messages["message_8"]);

          $player->sendMessage($page_nine_messages["message_9"]);

          $player->sendMessage($page_nine_messages["message_10"]);

        } else if($command[1] === "10" or $command[1] === "ten") {

          $page_ten_messages = $this->cfg->get("page_10");

          $player->sendMessage($page_ten_messages["message_1"]);

          $player->sendMessage($page_ten_messages["message_2"]);

          $player->sendMessage($page_ten_messages["message_3"]);

          $player->sendMessage($page_ten_messages["message_4"]);

          $player->sendMessage($page_ten_messages["message_5"]);

          $player->sendMessage($page_ten_messages["message_6"]);

          $player->sendMessage($page_ten_messages["message_7"]);

          $player->sendMessage($page_ten_messages["message_8"]);

          $player->sendMessage($page_ten_messages["message_9"]);

          $player->sendMessage($page_ten_messages["message_10"]);

        }

        }

      }

    }
/*Plugin OnEntityDamagezz*/
  public function onEntityDamagezz(EntityDamageEvent $event){
    $entity = $event->getEntity();
    if($event instanceof EntityDamageByEntityEvent && $event->getDamager() instanceof Player){
      foreach($this->EffectKatana->get("effects") as $itemid => $effectid){
        if($event->getDamager()->getInventory()->getItemInHand()->getId() === $itemid && $event->getDamager()->hasPermssion("use.katana.power")){
          $effectlevel = $this->EffectKatana->get("effect-level") - 1;
          $effecttime = $this->EffectKatana->get("effect-time");
          $particlevisible = $this->EffectKatana->get("particles-visible");
          $effect = Effect::getEffect($effectid);
          $effect->setAmplifier($effectlevel);
          $effect->setDuration($effecttime * 20);
          $effect->setVisible($particlevisible);
          $entity->addEffect($effect);
        }
      }
    }
  }
/*Plugin OnGBTHold*/
	public function onGBTHold(PlayerItemHeldEvent $e){
		$i = $e->getItem();
		$p = $e->getPlayer();
		if($e->isCancelled()) return;
   if($e->getItem()->getId() === 341){
			$p->sendPopup("§aG§ba§cc§dh§ea§fb§1o§2x §3T§4i§5c§6k§7e§8t§9s");
		}
	}
/*Plugin OnPlayerMove*/
	public function onPlayerMove(PlayerMoveEvent $event){
		$player = $event->getPlayer();
		$block = $player->getLevel()->getBlock($player->floor()->subtract(0, 1));
		if($this->getConfig()->get($block->getId()) !== false){
			$distance = $this->getConfig()->get($block->getId());
			$from = $event->getFrom();
			$to = $event->getTo();
			if(!is_numeric($distance)) $distance = 5;
			$player->setMotion((new Vector3(($to->x - $from->x) * ($distance / 5), 0.5, ($to->z - $from->z) * ($distance / 5))));
			$player->getLevel()->addParticle(new ExplodeParticle($player->getPosition()->add(mt_rand(0, 10) / 10 - 0.5, mt_rand(0, 4) / 10 - 0.2, mt_rand(0, 10) / 10 - 0.5)));
			$player->getLevel()->addParticle(new ExplodeParticle($player->getPosition()->add(mt_rand(0, 10) / 10 - 0.5, mt_rand(0, 4) / 10 - 0.2, mt_rand(0, 10) / 10 - 0.5)));
			$player->getLevel()->addParticle(new ExplodeParticle($player->getPosition()->add(mt_rand(0, 10) / 10 - 0.5, mt_rand(0, 4) / 10 - 0.2, mt_rand(0, 10) / 10 - 0.5)));
			$player->getLevel()->addParticle(new ExplodeParticle($player->getPosition()->add(mt_rand(0, 10) / 10 - 0.5, mt_rand(0, 4) / 10 - 0.2, mt_rand(0, 10) / 10 - 0.5)));
			$player->getLevel()->addParticle(new ExplodeParticle($player->getPosition()->add(mt_rand(0, 10) / 10 - 0.5, mt_rand(0, 4) / 10 - 0.2, mt_rand(0, 10) / 10 - 0.5)));
			$player->getLevel()->addParticle(new ExplodeParticle($player->getPosition()->add(mt_rand(0, 10) / 10 - 0.5, mt_rand(0, 4) / 10 - 0.2, mt_rand(0, 10) / 10 - 0.5)));
			$player->getLevel()->addParticle(new BubbleParticle($player->getPosition()->add(mt_rand(0, 10) / 10 - 0.5, mt_rand(0, 4) / 10 - 0.2, mt_rand(0, 10) / 10 - 0.5)));
			$player->getLevel()->addParticle(new BubbleParticle($player->getPosition()->add(mt_rand(0, 10) / 10 - 0.5, mt_rand(0, 4) / 10 - 0.2, mt_rand(0, 10) / 10 - 0.5)));
			$player->getLevel()->addParticle(new BubbleParticle($player->getPosition()->add(mt_rand(0, 10) / 10 - 0.5, mt_rand(0, 4) / 10 - 0.2, mt_rand(0, 10) / 10 - 0.5)));
			$player->getLevel()->addParticle(new BubbleParticle($player->getPosition()->add(mt_rand(0, 10) / 10 - 0.5, mt_rand(0, 4) / 10 - 0.2, mt_rand(0, 10) / 10 - 0.5)));
			$player->getLevel()->addParticle(new BubbleParticle($player->getPosition()->add(mt_rand(0, 10) / 10 - 0.5, mt_rand(0, 4) / 10 - 0.2, mt_rand(0, 10) / 10 - 0.5)));
			$player->getLevel()->addParticle(new BubbleParticle($player->getPosition()->add(mt_rand(0, 10) / 10 - 0.5, mt_rand(0, 4) / 10 - 0.2, mt_rand(0, 10) / 10 - 0.5)));
			$player->getLevel()->addSound(new BlazeShootSound($player->getPosition()));
			if($player->hasEffect(Effect::JUMP)) $player->removeEffect(Effect::JUMP);
			$player->addEffect(Effect::getEffect(8)->setDuration($distance * 2)->setAmplifier(255)->setAmbient(false)->setVisible(true));
		}
	}

/*/
 *  More Features Coming Soon.
 *  The Plugin Only For My Server.
/*/









}

class ArchParticles extends PluginTask {
  public function __construct($plugin)
  {
    $this->plugin = $plugin;
    parent::__construct($plugin);
  }

  public function onRun($tick){
    $level = $this->plugin->getServer()->getDefaultLevel();
    $spawn = $this->plugin->getServer()->getDefaultLevel()->getSafeSpawn();
    $r = rand(1,300);
    $g = rand(1,300);
    $b = rand(1,300);
    $x = $spawn->getX();
    $y = $spawn->getY();
    $z = $spawn->getZ();
    $center = new Vector3($x, $y, $z);
    $radius = 0.05;
    $count = 100;
    $particle = new DustParticle($center, $r, $g, $b, 1);
                for($yaw = 0, $y = $center->y; $y < $center->y + 4; $yaw += (M_PI * 2) / 20, $y += 1 / 20){
                  $x = -sin($yaw) + $center->x;
                  $z = cos($yaw) + $center->z;
                  $particle->setComponents($x, $y, $z);
                  $level->addParticle($particle);
}
  }
}
?>
