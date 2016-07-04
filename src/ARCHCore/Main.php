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

 public $players = array();

    public function dataPath(){

      return $this->getDataFolder();

    }
/*Plugins OnEnable*/
   public function onEnable(){

		  @mkdir($this->getDataFolder());
@mkdir($this->getDataFolder()."Stats/");

		  @mkdir($this->getDataFolder());
@mkdir($this->getDataFolder()."Players/");

		$this->makeSaveFiles();

    $this->getServer()->getScheduler()->scheduleRepeatingTask(new ArchParticles($this), 10);

		 		$this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this,"popupStats"]),15);
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
/*Plugin AddStrikex*/
    public function addStrikex(Player $p, $height){

$level = $p->getLevel();

$light = new AddEntityPacket();

$light->type = 93;

$light->eid = Entity::$entityCount++;

$light->metadata = array();

$light->speedX = 0;

$light->speedY = 0;

$light->speedZ = 0;

$light->yaw = $p->getYaw();

$light->pitch = $p->getPitch();

$light->x = $p->x;

$light->y = $p->y+$height;

$light->z = $p->z;

Server::broadcastPacket($level->getPlayers(),$light);

   }
/*Plugin OnJoinxez*/
    public function onJoinxez(PlayerJoinEvent $e){
	$p = $e->getPlayer();
	$light = new AddEntityPacket();
        $light->type = 93;
        $light->eid = Entity::$entityCount++;
        $light->metadata = array();
        $light->speedX = 0;
        $light->speedY = 0;
        $light->speedZ = 0;
        $light->yaw = $p->getYaw();
        $light->pitch = $p->getPitch();
        $light->x = $p->x;
        $light->y = $p->y;
        $light->z = $p->z;
        $p->dataPacket($light);
    	
    }
/*Plugin OnQuitxez*/
    public function onQuitxez(PlayerQuitEvent $e){
	$p = $e->getPlayer();
	$light = new AddEntityPacket();
        $light->type = 93;
        $light->eid = Entity::$entityCount++;
        $light->metadata = array();
        $light->speedX = 0;
        $light->speedY = 0;
        $light->speedZ = 0;
        $light->yaw = $p->getYaw();
        $light->pitch = $p->getPitch();
        $light->x = $p->x;
        $light->y = $p->y;
        $light->z = $p->z;
        $p->dataPacket($light);
    	
    }
/*Plugin OnDamagexzx*/
    public function onDamagexzx(EntityDamageEvent $e){
        /** @var Player $p */
        $p = $e->getEntity();
        if (!$p instanceof Player){
            return;
        }

        if ($e instanceof EntityDamageByEntityEvent){
            /** @var Player $pl */
            $pl = $e->getDamager();
            if (!$pl instanceof Player){
                return;
            }
            $it = $pl->getInventory()->getItemInHand();
            if (!$it->hasEnchantments()){
                return;
            }
            $en = $it->getEnchantments();
            foreach ($en as $ench){
                $lvl = $ench->getLevel();
                switch ($ench->getId()){
                    case 9:
                        $e->setDamage($e->getDamage()+($lvl*1.25));
                        break;
                    case 12:
                        $e->setKnockback($e->getKnockBack()+($lvl*0.3));
                        break;
                    case 13:
                        if (!$e->isCancelled()){
                            $p->setOnFire($lvl*4);
                        }
                        break;
                    case 19:
                        $dmg = \round((($lvl+1)/4));
                        $e->setDamage($e->getDamage()+$dmg);
                        break;
                    case 20:
                        $e->setKnockBack($e->getKnockBack()+($lvl*0.4));
                        break;
                    case 21:
                        if (!$e->isCancelled()){
                            $p->setOnFire(5);
                        }
                        break;
                    case 22:
                        $pl->getInventory()->addItem(Item::ARROW, 0, 1);
                        break;
                }
            }
        }

        foreach ($p->getInventory()->getArmorContents() as $item){
            $eng = $item->getEnchantments();
            foreach ($eng as $enchantment){
                $lvl = $enchantment->getLevel();
                switch ($enchantment->getId()){
                    case 0:
                        $e->setDamage($e->getDamage() - (($lvl*0.04)*$e->getDamage()));
                        break;
                    case 1:
                        if ($e->getCause() > 4 && $e->getCause() < 8){
                            $e->setDamage($e->getDamage() - (($lvl*0.12)*$e->getDamage()));
                        }
                        break;
                    case 2:
                        if ($e->getCause() == 4){
                            $e->setDamage($e->getDamage() - (($lvl*0.15)*$e->getDamage()));
                        }
                        break;
                    case 3:
                        if ($e->getCause() > 8 && $e->getCause() < 11){
                            $e->setDamage($e->getDamage() - (($lvl*0.15)*$e->getDamage()));
                        }
                        break;
                    case 4:
                        if ($e->getCause() == 2){
                            $e->setDamage($e->getDamage() - (($lvl*0.12)*$e->getDamage()));
                        }
                        break;
                    case 7:
                        if ($e instanceof EntityDamageByEntityEvent){
                            /** @var Player $pl */
                            $pl = $e->getDamager();
                            Server::getInstance()->getPluginManager()->callEvent($ev = new EntityDamageEvent($pl, 14, $lvl*2));
                            if ($ev->isCancelled() || $ev->getDamage() <= 0){
                                break;
                            }
                            $pl->attack($lvl*2, $ev);
                        }
                        break;
                }
            }
        }
    }
/*Plugin OnBreakszz*/
    public function onBreakszz(BlockBreakEvent $e){
        $p = $e->getPlayer();
        if (!$p->getInventory()->getItemInHand()->hasEnchantments()){
            return;
        }
        $ench = $p->getInventory()->getItemInHand()->getEnchantments();
        foreach ($ench as $en){
            $lvl = $en->getLevel();
            switch ($en->getId()){
                case 16:
                    $item = [$e->getBlock()];
                    $e->setDrops($item);
                    break;
                case 17:
                    if (\mt_rand(1, (6-$lvl)) === 2){
                        $i = $p->getInventory()->getItemInHand();
                        $i->setDamage($i->getDamage()+1);
                    }
                    break;
                case 18:
                    switch ($e->getBlock()->getId()){
                        case 16:
                            $drop = \mt_rand(3, 3+$lvl);
                            $e->setDrops([Item::get(263, 0, $drop)]);
                            break;
                        case 21:
                            $drop = \mt_rand(5, 5+$lvl);
                            $e->setDrops([Item::get(351, 4, $drop)]);
                            break;
                        case 56:
                            $drop = \mt_rand(1, 1+$lvl);
                            $e->setDrops([Item::get(264, 0, $drop)]);
                            break;
                        case 73:
                            $drop = \mt_rand(5, 5+$lvl);
                            $e->setDrops([Item::get(331, 0, $drop)]);
                            break;
                        case 89:
                            $e->setDrops([Item::get(16, 0, 4)]);
                            break;
                        case 129:
                            $drop = \mt_rand(1, \round(1+($lvl/3)));
                            $e->setDrops([Item::get(129, 0, $drop)]);
                            break;
                        case 153:
                            $drop = \mt_rand(2, 2+$lvl);
                            $e->setDrops([Item::get(406, 0, $drop)]);
                            break;
                    }
                    break;
            }
        }
    }	 
/*Plugin TranslateColors*/
	public function translateColors($symbol, $message){
		
		$message = str_replace($symbol."0", TextFormat::BLACK, $message);
		$message = str_replace($symbol."1", TextFormat::DARK_BLUE, $message);
		$message = str_replace($symbol."2", TextFormat::DARK_GREEN, $message);
		$message = str_replace($symbol."3", TextFormat::DARK_AQUA, $message);
		$message = str_replace($symbol."4", TextFormat::DARK_RED, $message);
		$message = str_replace($symbol."5", TextFormat::DARK_PURPLE, $message);
		$message = str_replace($symbol."6", TextFormat::GOLD, $message);
		$message = str_replace($symbol."7", TextFormat::GRAY, $message);
		$message = str_replace($symbol."8", TextFormat::DARK_GRAY, $message);
		$message = str_replace($symbol."9", TextFormat::BLUE, $message);
		$message = str_replace($symbol."a", TextFormat::GREEN, $message);
		$message = str_replace($symbol."b", TextFormat::AQUA, $message);
		$message = str_replace($symbol."c", TextFormat::RED, $message);
		$message = str_replace($symbol."d", TextFormat::LIGHT_PURPLE, $message);
		$message = str_replace($symbol."e", TextFormat::YELLOW, $message);
		$message = str_replace($symbol."f", TextFormat::WHITE, $message);
		
		$message = str_replace($symbol."k", TextFormat::OBFUSCATED, $message);
		$message = str_replace($symbol."l", TextFormat::BOLD, $message);
		$message = str_replace($symbol."m", TextFormat::STRIKETHROUGH, $message);
		$message = str_replace($symbol."n", TextFormat::UNDERLINE, $message);
		$message = str_replace($symbol."o", TextFormat::ITALIC, $message);
		$message = str_replace($symbol."r", TextFormat::RESET, $message);
		
		return $message;
	}
/*Plugin test*/
	public function test(CommandSender $sender, Command $cmd, $label, array $sub){
		$nbt = new CompoundTag("", ["Pos" => new ListTag("Pos", [new DoubleTag("", $sender->getx()),new DoubleTag("", $sender->gety() + $sender->getEyeHeight()),new DoubleTag("", $sender->getz()) ]),"Motion" => new ListTag("Motion", [new DoubleTag("", -sin($sender->getyaw() / 180 * M_PI) * cos($sender->getPitch() / 180 * M_PI)),new DoubleTag("", -sin($sender->getPitch() / 180 * M_PI)),new DoubleTag("", cos($sender->getyaw() / 180 * M_PI) * cos($sender->getPitch() / 180 * M_PI)) ]),"Rotation" => new ListTag("Rotation", [new FloatTag("", $sender->getyaw()),new FloatTag("", $sender->getPitch()) ]) ]);
		$arrow = new Arrow($sender->chunk, $nbt, $sender);
		$ev = new EntityShootBowEvent($sender, Item::get(264, 0, 0), $arrow, 1.5);
		$this->getServer(0)->getPluginManager()->callEvent($ev);
		if($ev->isCancelled()) $arrow->kill();
		else $arrow->spawnToAll();
		return true;
	}
/*Plugin GetDataFolder*/
		public function GetDataFolderMCPE(){
			return $this->getDataFolder();
		}
/*Plugin MakeFile*/
		public function makefile(PlayerJoinEvent $ev){
		$ign=$ev->getPlayer()->getName();
		$p=$ev->getPlayer();
		$player=$p;
		 $this->PlayerFile = new Config($this->getDataFolder()."Players/".$ign.".yml", Config::YAML);
		}
/*/
 * Wanted To Use Snowball But I'm Lazy...
 *
/*/
/*Plugin FireBow*/
  public function FireBow(EntityShootBowEvent $event){
    $entity = $event->getEntity();
    if($entity instanceof Player){
      if($entity->hasPermission("Fire.Bows")){
        $event->getProjectile()->setOnFire(500000000 * 20);
      }
    }
  }
/*Plugin OnJoinStats*/
		public function onJoinStats(PlayerJoinEvent $ev){
		$ign=$ev->getPlayer()->getName();
		$p=$ev->getPlayer();
		$player=$p;
		 $this->PlayerFile = new Config($this->getDataFolder()."Stats/".$ign."_Stats.yml", Config::YAML);
		 
		 if($this->PlayerFile->get("Deaths") === 0 && $this->PlayerFile->get("Kills") === 0){

         }else{

		$this->addPlayer($p);

		}
  }
/*Plugin OnHitStats*/
public function onHitStats(EntityDamageEvent $ev){


$p = $ev->getEntity();

if($ev instanceof EntityDamageByEntityEvent){
$damager = $ev->getDamager();
if($damager instanceof Player){

$this->DamagerFile = new Config($this->getDataFolder()."Stats/".$damager->getName()."_Stats.yml", Config::YAML);

$this->PlayerFile = new Config($this->getDataFolder()."Stats/".$p->getName()."_Stats.yml", Config::YAML);
}
}
}
/*Plugin OnPlayerLoginStats*/
		 public function onPlayerLoginStats(PlayerPreLoginEvent $event){
        $ign = $event->getPlayer()->getName();
        $player = $event->getPlayer();
        $file = ($this->getDataFolder()."Stats/".$ign."_Stats.yml");  
      if(!file_exists($file)){
                $this->PlayerFile = new Config($this->getDataFolder()."Stats/".$ign."_Stats.yml", Config::YAML);
                $this->PlayerFile->set("Deaths", 0);
                $this->PlayerFile->set("Kills", 0);
                $this->PlayerFile->set("Mining_Level", 0);
                
                $this->PlayerFile->set("Blocks_Broken", 0);
                $this->PlayerFile->set("Power_Level", 0);
                $this->PlayerFile->set("Sword_Level", 0);
                $this->PlayerFile->save();
            }
            $this->PlayerFile = new Config($this->getDataFolder()."Stats/".$ign."_Stats.yml", Config::YAML);
            
        } 
/*Plugin OnDeathStats*/
        public function onDeathStats(PlayerDeathEvent $ev){
        $p=$ev->getEntity();
        $player=$ev->getEntity();
        $ign=$player->getName();
        $this->PlayerFile = new Config($this->getDataFolder()."Stats/".$ign."_Stats.yml", Config::YAML);
        $i=$this->PlayerFile->get("Deaths");
        $ii=$this->PlayerFile->get("Power_Level");
       $n = $i+1;
       $p=$ii-3;
       $this->PlayerFile->set("Deaths", $n);
       $this->PlayerFile->set("Power_Level", $p);
       $level = $player->getLevel();
                $level->addSound(new BlazeShootSound($player->getLocation()));
       $this->PlayerFile->save();
       
       $cause = $ev->getEntity()->getLastDamageCause();

	 			 if($ev->getEntity()->getLastDamageCause() instanceof EntityDamageByEntityEvent){
                   $killer = $ev->getEntity()->getLastDamageCause()->getDamager();
                $this->PlayerFile = new Config($this->getDataFolder()."Stats/".$killer->getName()."_Stats.yml", Config::YAML);
                if(!$killer->hasPermission("vip")){
                $iii=$this->PlayerFile->get("Kills");
                $nn = $iii+1;
                $this->PlayerFile->set("Kills", $nn);
                $iiii=$this->PlayerFile->get("Power_Level");
                $nnn = $iiii+3;
                $this->PlayerFile->set("Power_Level", $nnn);
                $level = $killer->getLevel();
                $level->addSound(new GhastShootSound($killer->getLocation()));
                $this->PlayerFile->save();
                $this->updatePlayer($killer);
      $this->removePlayer($player);
       
      $this->addPlayer($player);
                }else{
     $iii=$this->PlayerFile->get("Kills");
                $nn = $iii+1;
                $this->PlayerFile->set("Kills", $nn);
                $iiii=$this->PlayerFile->get("Power_Level");
                $nnn = $iiii+6;
                $this->PlayerFile->set("Power_Level", $nnn);
                $killer->sendTip("§5+6 Power Level");
                $level = $killer->getLevel();
                $level->addSound(new GhastShootSound($killer->getLocation()));

                $this->PlayerFile->save();
      $this->updatePlayer($killer);
      $this->removePlayer($player);
       
      $this->addPlayer($player);
      }
        
       }
       }
/*Plugin OnBreakStats*/
       public function onBreakStats(BlockBreakEvent $ev){
       $player=$ev->getPlayer();
       $ign=$player->getName();
        $this->PlayerFile = new Config($this->getDataFolder()."Stats/".$ign."_Stats.yml", Config::YAML);
        $i=$this->PlayerFile->get("Blocks_Broken");
       $n = $i+1;
       $this->PlayerFile->set("Blocks_Broken", $n);
       $this->PlayerFile->save();
       }
       public function popupStats(){
       foreach($this->getServer()->getOnlinePlayers() as $p){
       $ign=$p->getName();
       $this->PlayerFile = new Config($this->getDataFolder()."Stats/".$ign."_Stats.yml", Config::YAML);
       if($p->getLevel()->getName() == "nt"){
         if($this->PlayerFile->get("Kills") > 0 && $this->PlayerFile->get("Deaths") > 0){

         
           
              $p->sendTip("§5KillStreaks§8: §c".$this->players[$p->getName()]["kills"]);
            
       }
         
       }
              if($p->getLevel()->getName() == "BRAWLPE"){
        $p->sendPopup("§8> §5K§8: §c".$this->PlayerFile->get("Kills")."§8- §5D§8: §c".$this->PlayerFile->get("Deaths")."\n§8> §5KDR§8: §c".round($this->PlayerFile->get("Kills")/$this->PlayerFile->get("Deaths"), 2)."§8- §5KillStreaks§8: §c".$this->players[$p->getName()]["kills"]);
		}
		}
  }
		
    
    public function updatePlayer(Player $player) {
        
            $this->players[$player->getName()] = array(
              "kills" => $this->players[$player->getName()]["kills"] + 1  
            );
        
    }

    public function addPlayer(Player $player) {
        $this->players[$player->getName()] = array(
            "kills" => 0
        );
    }
    
    public function isPlayerSet(Player $player) {
        return in_array($player->getName(), $this->players);
    }
    
    public function removePlayer(Player $player) {
        unset($this->players[$player->getName()]);
    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        $name = $sender->getName();
        $ign = $sender->getName();

        $this->PlayerFile = new Config($this->getDataFolder()."Stats/".$ign."_Stats.yml", Config::YAML);
        
        if(strtolower($cmd->getName()) === 'stats'){

          if($sender->hasPermission("rpg.stats")) {

            if($this->PlayerFile->get("Kills") > 0 or $this->PlayerFile->get("Deaths") > 0 or $this->PlayerFile->get("Power_Level") > 0 or $this->PlayerFile->get("Sword_Level") > 0 or $this->PlayerFile->get("Mining_Level") > 0){


           $sender->sendMessage("§l§b»§r§e".$ign." Status§0:"."\n§l§b»§r§5Power Level§8: §c".$this->PlayerFile->get("Power_Level")."\n§l§b»§r§5Sword Level§8: §c".$this->PlayerFile->get("Sword_Level")."\n§l§b»§r§5Mining Level§8: §c".$this->PlayerFile->get("Mining_Level")."\n§l§b»§r§5Kills§8: §c".$this->PlayerFile->get("Kills")."\n§l§b»§r§5Deaths§8: §c".$this->PlayerFile->get("Deaths")."\n§l§b»§r§5KDR§8: §c".round($this->PlayerFile->get("Kills")/$this->PlayerFile->get("Deaths"), 2));
        } else {
          $sender->sendMessage("§l§b»§r§e".$ign." Status§0:"."\n§l§b»§r§5Power Level§8: §c".$this->PlayerFile->get("Power_Level")."\n§l§b»§r§5Sword Level§8: §c".$this->PlayerFile->get("Sword_Level")."\n§l§b»§r§5Mining Level§8: §c".$this->PlayerFile->get("Mining_Level")."\n§l§b»§r§5Kills§8: §c".$this->PlayerFile->get("Kills")."\n§l§b»§r§5Deaths§8: §c".$this->PlayerFile->get("Deaths")."\n§l§b»§r§5KDR§8: §c".round($this->PlayerFile->get("Kills")/$this->PlayerFile->get("Deaths"), 2));
            }
          }
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
