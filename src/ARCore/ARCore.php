<?php

namespace ARCore;
/*/
 * Copyrights Of NeuroBinds Project Corps.
 *
 * You May Edit,Sell,Share And Contribute.
 *
 * Somehow I Hate This.
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
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\entity\EntityInventoryChangeEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\player\PlayerChatEvent;
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
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\level\LevelLoadEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBedEnterEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerHungerChangeEvent;
//items
use pocketmine\item\Slimeball;
use pocketmine\item\Item;
//commands
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandExecutor;
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
use pocketmine\entity\Entity;
use pocketmine\entity\Effect;
use pocketmine\entity\Arrow;
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
//trying to add clans
use ARCore\Clans\FactionCommands;
use ARCore\Clans\FactionListener;
//others.
//use ARCore\Enchantment\EnchantManager;
//pets implements
use ARCore\Pets\PetCommand;//
use ARCore\Pets\OcelotPet;//
use ARCore\Pets\BatPet;//
use ARCore\Pets\BlazePet;//
use ARCore\Pets\BlockPet;//
use ARCore\Pets\ChickenPet;//
use ARCore\Pets\MagmaPet;//
use ARCore\Pets\Pets;//
use ARCore\Pets\PigPet;//
use ARCore\Pets\RabbitPet;//
use ARCore\Pets\SheepPet;//
use ARCore\Pets\SilverfishPet;//
use ARCore\Pets\SpiderPet;//
use ARCore\Pets\WitchPet;//
use ARCore\Pets\WolfPet;//
use ARCore\Pets\CowPet;//
use ARCore\Pets\CreeperPet;//
use ARCore\Pets\EndermanPet;//
use ARCore\Pets\HuskPet;//
use ARCore\Pets\IronGolemPet;//
//Auths Implement
use ARCore\Auth\EventListener;
use ARCore\Auth\Tasks\PopupTipTick;
use ARCore\Auth\Tasks\TimeoutTask;
use ARCore\Auth\Tasks\MessageTick;
use ARCore\Auth\Commands\ChangePasswordCommand;
use ARCore\Auth\Commands\ForgotPasswordCommand;
use ARCore\Auth\Commands\LoginCommand;
use ARCore\Auth\Commands\LogoutCommand;
use ARCore\Auth\Commands\PinCommand;
use ARCore\Auth\Commands\RegisterCommand;
use ARCore\Auth\Commands\ResetPasswordCommand;
//economys
use onebone\economyapi\EconomyAPI;
use ARCore\Particle\ParticleManager;
use ARCore\AntiHack\AntiHack;
use ARCore\ChatFilter\ChatFilter;
use ARCore\ChatFilter\ChatFilterTask;

//use ARCore\Listener\EventListenerz;
//use  ARCore\AntiHack\AntiHack;//NON-OFFICIAL ANTIHACK

class ARCore extends PluginBase implements Listener{

 //TODO
 //Dont Use EconomyAPI [Write Our Own Currency](Why??A:Because this plugin is a core and can be as lagg as freak if there another plugin)[Core are suppose to be ALONE]
 //Rewrite Pets [HURRY]      
 //Re-Implement AntiHack(NON-FUNCTIONAL)
 //Add Background Sounds(URGENT)
 //Rewrite Auths(Having Unknown Bugs)
 //Custom Ranks(GOOD BYE PUREPERMS)
 //BLABLA   

    public $players = [];
    public $particle = [];
	/*Clans*/
	public $db;
	public $prefs;
  /*Pets*/
	public static $pet;
	public static $petState;
	public static $isPetChanging;
	public static $type;
	public $pettype;
	public $price;
	public $wishPet;
  /*Inventory Saver*/
	public $inventories;
  /*Auths*/
	public $authenticated;
	public $confirmPassword;
	public $messagetick;
	public $tries;


/*Plugins OnEnable*/
   public function onEnable(){

		$this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this,"onRun"]), 20);
  //  $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    //$this->getCommand("quest")->setExecutor(new QuestCommands($this));
   // $this->getCommand("party")->setExecutor(new PartyCommands($this));
    //$this->getServer ()->getScheduler()->scheduleRepeatingTask (new ManaTask($this), 40);
		//$this->mmorpg = new Config($this->getDataFolder() . "MMOSettings.yml", CONFIG::YAML, array(
            //"TargetWorld" => "",
           // "Alllow Block Placing" => false,
           // "Allow Block Breaking" => false,
           // "Disable Item Losing" => true,
      //  ));

		AntiHack::enable($this);
        $this->antihack = AntiHack::getInstance();

		//$this->getServer()->getPluginManager()->registerEvents(new \ARCore\Pets\main(), $this);
/*Enchant Manager */
       // $this->getServer()->getPluginManager()->registerEvents(new EnchantManager($this), $this);
       //Trying To Squash All The Setting (yml file)
       //might be ugly
       /*
       $this->arcore = new Config($this->getDataFolder() . "ARCoreSettings.yml", CONFIG::YAML, array(

       ))







      */
       //
//Using EconomyAPI by onebone
			$this->api = EconomyAPI::getInstance();
   // $this->getServer()->getPluginManager()->registerEvents(new EventListenerz($this), $this);
//Inventory Saver OnEnable//
        @mkdir($this->getDataFolder());
        $this->inventories = new \SQLite3($this->getDataFolder()."inventories.db", SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $level = strtolower($this->getServer()->getDefaultLevel()->getFolderName());
        $this->inventories->exec("CREATE TABLE IF NOT EXISTS `$level` (name TEXT PRIMARY KEY, slots BLOB, armor BLOB)");
//Inventory Saver OnEnable///
///Auth OnEnable///
		$this->auth = new Config($this->getDataFolder() . "AuthsSetting.yml", CONFIG::YAML, array(
		"join-message" => "\n \n \n§l§8-§6»\n \n  §r§8This Server Using An Authentication System.",
		"login" => "\n \n  §8Please Login By Typing In §b/log <password>\n \n§l§8-§6»",
		"login-popup" => "§8Not Authenticate",
		"authentication-success" => "§aYou Have Being Authenticate!",
		"already-authenticated" => "§8You have already logged in.",
		"incorrect-password" => "§8Incorrect password.You Have §5{tries} §8Tries left.",
		"not-registered" => "§5%null%{error}",
		"register" => "\n \n  §8Please Login By Typing In §b/reg <password> <confirm password>\n \n \n§l§8-§6»",
		"register-popup" => "§8Not Authenticate",
		"register-success" => "§aYou have been registered.Your pin is {pin}.",
		"already-registered" => "§8You are already registered.",
		"password-too-short" => "§8Password is too short.",
		"password-not-match" => "§5%null%{error}",
		"confirm-password" => "§dPlease confirm your password.",
		"change-password-success" => "§eYour password has been changed.",
		"forgot-password-success" => "§aYour password has been changed. Your new pin is {pin}.",
		"incorrect-pin" => "§cIncorrect pin.",
		"password-reset-success" => "§aPlayers password has been reset.",
		"not-registered-two" => "§cPlayer not registered.",
		"pin" => "§aYour pin= {pin}",
		"dont-say-password" => "§5%null%{error}",
		"timeout-message" => "§cLogin Timeout",
		"too-many-tries" => "§cToo Many Tries to Login",
		"timeout" => 12000,
		"allow-movement" => false,
		"chat-login" => false,
		"auto-authentication" => false,
		"minimum-password-length" => 6,
		"tries" => 10,
		"popup" => true,
		"seconds-til-next-message" => 1000000,
		"invisible" => true,
		"blindness" => true,
		"see-messages" => false,
		));
        if(!file_exists($this->getDataFolder() . "AuthsData.db")) {
            $this->auths = new \SQLite3($this->getDataFolder() . "AuthsData.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
            $this->auths->exec("CREATE TABLE players (name TEXT PRIMARY KEY, password TEXT, pin INT, uuid INT, attempts INT);");
        } else {
            $this->auths = new \SQLite3($this->getDataFolder() . "AuthsData.db", SQLITE3_OPEN_READWRITE);

                //$this->auths->exec("ALTER TABLE players ADD COLUMN pins INT");

                //$this->auths->exec("ALTER TABLE players ADD COLUMN attempts INT");
            }
		//$this->getServer()->getCommandMap()->register('pets', new PetCommand($this,"pets"));
        $this->getServer()->getCommandMap()->register('cpwd', new ChangePasswordCommand('cpwd', $this));
        $this->getServer()->getCommandMap()->register('fpwd', new ForgotPasswordCommand('fpwd', $this));
        $this->getServer()->getCommandMap()->register('log', new LoginCommand('log', $this));
        $this->getServer()->getCommandMap()->register('logout', new LogoutCommand('logout', $this));
        $this->getServer()->getCommandMap()->register('reg', new RegisterCommand('reg', $this));
        $this->getServer()->getCommandMap()->register('pin', new PinCommand('pin', $this));
        $this->getServer()->getCommandMap()->register('rpwd', new ResetPasswordCommand('rpwd', $this));
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new MessageTick($this), 20);
        if($this->auth->get("popup")) {
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new PopupTipTick($this), 20);
        }
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

///Auth OnEnable///

//config for custom //		
		$this->custom = new Config($this->getDataFolder() . "CustomSetting.yml", CONFIG::YAML, array(
		"ServerName" => "§aArch§eRPG",
		"LoadDefaultWorld" => "Lobby-202",
		"SetPlayerFoodBarOnJoin" => 20000,
		"SetMaxPlayerHealthOnJoin" => 1,
		"SetPlayerHealthOnJoin" => 40,
		"SetPlayerFoodBarOnRespawn" => 20000,
		"SetMaxPlayerHealthOnRespawn" => 1,
		"SetPlayerHealthOnRespawn" => 40,
		"DropDeath" => "388,0,1",
		"NoVoid-SetPlayerMaxHealth" => 1,
		"NoVoid-SetPlayerHealth" => 1,
		"NoVoid-SetPlayerFood" => 200000,
        "Player-Gain-Coins-PerKill" => 20,
        "Player-Lose-Coins-PerDeath" => 10,
        "Player-Gains-Coins-For-Killing-Message" => "You Gains 20 Coins For Killed A Player.",
        "Player-Lose-Coins-For-Dying-Message" => "You Lose 10 Coins For Being Killed By A Player.",
		));


/////////THE START OF PETS [ON ENABLE]/////////	
/*	
		$this->PetPrices = new Config($this->getDataFolder() . "PetPrices.yml", CONFIG::YAML, array(
		"PetOffMsg" => "§b[Pet -> Me]§f - §eOkay, Ill be around",
		"SpawnDogMsg" => "§bYour Dog is Here ! §aCost - $10000",
		"DogCost" => 10000,
		"SpawnCatMsg" => "§bYour Cat is Here ! §aCost - $10000",
		"CatCost" => 10000,
		"SpawnRabbitMsg" => "§bYour Rabbit is Here ! §aCost - $10000",
		"RabbitCost" => 10000,
		"SpawnPigMsg" => "§bYour Pig is Here ! §aCost - $5000",
		"PigCost" => 5000,
		"SpawnSheepMsg" => "§bYour Sheep is Here ! §aCost - $5000",
		"SheepCost" => 5000,
		"SpawnChickenMsg" => "§bYour Chicken is Here ! §aCost - $5000",
		"ChickenCost" => 5000,
        "SpawnSilverfishMsg" => "§bYour Silverfish is Here ! §aCost - $100000",
        "SilverfishCost" => 100000,
        "SpawnMagmaMsg" => "§bYour Magma is Here ! §aCost - $5000",
        "MagmaCost" => 5000,
        "SpawnBatMsg" => "§bYour Bat is Here ! §aCost - $5000", 
		"BatCost" => 5000,
        "SpawnBlockMsg" => "§bYour Blockin is Here ! §aCost - $5000",
        "BlockCost" => 5000,
        "PetPrices" => "§bTypes\n §aDog $10000\n §eCat $10000\n §aRabbit $10000\n §ePig $5000\n §aSheep $5000\n §eChicken $5000\n §aSilverfish $100000\n §eMagma $5000\n §aBat $5000\n §eBlock $5000.",
		));
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder() . "PetPlayer");
		$server = Server::getInstance();
		$server->getCommandMap()->register('pets', new PetCommand($this,"pets"));
		Entity::registerEntity(OcelotPet::class);
		Entity::registerEntity(WolfPet::class);
		Entity::registerEntity(PigPet::class);
		Entity::registerEntity(SheepPet::class);
		Entity::registerEntity(RabbitPet::class);
		Entity::registerEntity(ChickenPet::class);
		Entity::registerEntity(BatPet::class);
		Entity::registerEntity(MagmaPet::class);
		Entity::registerEntity(SilverfishPet::class);
		Entity::registerEntity(BlockPet::class);
		$this->saveDefaultConfig();
*/
/////////Ends Of Pets [ On Enable]///////

/////Start Of Clans [OnEnable]/////
		@mkdir($this->getDataFolder());
		
		if(!file_exists($this->getDataFolder() . "PlayerClanBanned.txt")) {
			$file = fopen($this->getDataFolder() . "PlayerClanBanned.txt", "w");
			$txt = "Admin:admin:Staff:staff:Owner:owner:Builder:builder:Op:OP:op";
			fwrite($file, $txt);
		}
		$this->getServer()->getPluginManager()->registerEvents(new FactionListener($this), $this);
		$this->fCommand = new FactionCommands($this);
		
		$this->prefs = new Config($this->getDataFolder() . "ClansOptions.yml", CONFIG::YAML, array(
		"CreateCost" => 3000,
		"ClaimCost" => 100000,
		"OverClaimCost" => 25000,
		"AllyCost" => 5000,
		"AllyPrice" => 5000,
		"SetHomeCost" => 150,
		"MaxFactionNameLength" => 4,
		"MaxPlayersPerFaction" => 100,
		"OnlyLeadersAndOfficersCanInvite" => true,
		"OfficersCanClaim" => false,
		"PlotSize" => 30,
        "PlayersNeededInFactionToClaimAPlot" => 5,
        "PowerNeededToClaimAPlot" => 1000,
        "PowerNeededToSetOrUpdateAHome" => 250,
        "PowerGainedPerPlayerInFaction" => 50,
        "PowerGainedPerKillingAnEnemy" => 15, 
		"PowerReducedPerDeathByAnEnemy" => 10,
        "PowerGainedPerAlly" => 100,
        "TheDefaultPowerEveryFactionStartsWith" => 0,
        "EnableOverClaim" => true,
		));
		$this->db = new \SQLite3($this->getDataFolder() . "FactionPower.db");
		$this->db->exec("CREATE TABLE IF NOT EXISTS master (player TEXT PRIMARY KEY COLLATE NOCASE, faction TEXT, rank TEXT);");
		$this->db->exec("CREATE TABLE IF NOT EXISTS confirm (player TEXT PRIMARY KEY COLLATE NOCASE, faction TEXT, invitedby TEXT, timestamp INT);");
		$this->db->exec("CREATE TABLE IF NOT EXISTS alliance (player TEXT PRIMARY KEY COLLATE NOCASE, faction TEXT, requestedby TEXT, timestamp INT);");
		$this->db->exec("CREATE TABLE IF NOT EXISTS motdrcv (player TEXT PRIMARY KEY, timestamp INT);");
		$this->db->exec("CREATE TABLE IF NOT EXISTS motd (faction TEXT PRIMARY KEY, message TEXT);");
		$this->db->exec("CREATE TABLE IF NOT EXISTS plots(faction TEXT PRIMARY KEY, x1 INT, z1 INT, x2 INT, z2 INT);");
		$this->db->exec("CREATE TABLE IF NOT EXISTS home(faction TEXT PRIMARY KEY, x INT, y INT, z INT);");
		$this->db->exec("CREATE TABLE IF NOT EXISTS strength(faction TEXT PRIMARY KEY, power INT);");
		$this->db->exec("CREATE TABLE IF NOT EXISTS allies(ID INT PRIMARY KEY,faction1 TEXT, faction2 TEXT);");
/////Clans Ends On Enable////

//Todo.
/*
* Config. [DONE]
* Clean Up..
* ReWrite.
* Pure Code.
*/

//Under Here Making Config..

//load level/world
        $this->getServer()->loadLevel($this->custom->get("LoadDefaultWorld"));
/*Server Name*/
		$this->getServer()->getNetwork()->setName($this->custom->get("ServerName"));


///This Is To register Events!
///Dont Write It Again Or Else It Will Send 2 Events On A Row..
       $this->getServer()->getPluginManager()->registerEvents($this ,$this);
       $this->getLogger()->info("Enabled...");
       $this->getServer()->getDefaultLevel();
       $this->getServer()->getDefaultLevel()->setTime(50000);
       $this->getServer()->getDefaultLevel()->stopTime();
		//Pets OnEnable
		$this->getServer()->getCommandMap()->register('pets', new PetCommand($this,"pets"));
		Entity::registerEntity(ChickenPet::class);
		Entity::registerEntity(WolfPet::class);
		Entity::registerEntity(PigPet::class);
		Entity::registerEntity(BlazePet::class);
		Entity::registerEntity(MagmaPet::class);
		Entity::registerEntity(RabbitPet::class);
		Entity::registerEntity(BatPet::class);
		Entity::registerEntity(SilverfishPet::class);
		Entity::registerEntity(SpiderPet::class);
		Entity::registerEntity(CowPet::class);
		Entity::registerEntity(CreeperPet::class);
	    Entity::registerEntity(IronGolemPet::class);
        Entity::registerEntity(HuskPet::class);
        Entity::registerEntity(EndermanPet::class);
        Entity::registerEntity(SheepPet::class);
        Entity::registerEntity(WitchPet::class);
		Entity::registerEntity(BlockPet::class);

		$this->PetPrices = new Config($this->getDataFolder() . "PetPrices.yml", CONFIG::YAML, array(
        "BlazePrices" => 1000,
        "PigPrices" => 1000,
		"ChickenPrices" => 1000,
        "WolfPrices" => 1000,
	    "RabbitPrices" => 1000,
        "MagmaPrices" => 1000,
        "BatPrices" => 1000,
    	"SilverfishPrices" => 1000,
	    "SpiderPrices" => 1000,
        "CowPrices" => 1000,
		"CreeperPrices" => 1000,
		"IronGolemPrices" => 1000,
        "HuskPrices" => 1000,
	    "EndermanPrices" => 1000,
        "SheepPrices" => 1000,
        "WitchPrices" => 1000,
        "BlockPrices" => 1000,
		));
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder() . "PetPlayer");

       $this->getLogger()->notice("Pets Loaded!");
	   //Pets OnEnable
       //
       $this->getLogger()->notice("Clans Loaded!");
       $this->getLogger()->notice("Authentication Loaded!");
       $this->getLogger()->notice("Customize Player Loaded!");
       $this->getLogger()->notice("Particles Loaded!");
       $this->getLogger()->notice("ChatFilter Loaded!");
       $this->getLogger()->notice("AntiHacks Loaded!");

        $this->manager = new ParticleManager($this);
        $this->filter = new ChatFilter();

        $this->getServer()->getScheduler()->scheduleRepeatingTask(new ChatFilterTask($this), 30);
       
			}

   
/*Plugins OnDisable*/
   public function onDisable(){
       $this->getLogger()->info("Disabled...");
       $this->getLogger()->warning("Pets Unloaded!"); 
       $this->getLogger()->warning("Clans Unloaded!");
       $this->getLogger()->warning("Authentication Unloaded!");
       $this->getLogger()->warning("Customize Player Unloaded!");
       $this->getLogger()->warning("Particles Unloaded!");    
       $this->getLogger()->warning("ChatFilter Unloaded!");   
       $this->getLogger()->warning("AntiHacks Unloaded!");
       $this->db->close();
       $this->inventories->close();
        //$this->getConfig()->set('users', $this->users);, $this->users
      //  $this->getConfig()->save();
	
   }
   //AntiHack
    public function onCommandAntiHack(CommandSender $sender, Command $command, $label, array $args) {
        $subcommand = strtolower(array_shift($args));
        switch ($subcommand) {
            default:
                return false;
        }
    }
   /*
   ChatFilter
   */
    public function onPlayerChat(PlayerChatEvent $event) {
        if (!in_array($event->getPlayer()->getDisplayName()) && !$this->filter->check($event->getPlayer(), $event->getMessage())) {
            $event->setCancelled(true);
            $event->getPlayer()->sendMessage(TextFormat::RED . " I'm sorry, I can't let you say that.");
        }
    }
   /*
   PARTICLES
   
   */
    public function onCommandParticles(CommandSender $sender, Command $command, $label, array $args) {
        $subcommand = strtolower($command->getName('arparticles'));
        switch ($subcommand) {
            case "give";
                if(count($args) < 1){
                    array_unshift($args, $sender->getDisplayName());
                }

                /**
                 * Check perms, then give particles
                 */
                if ($sender->hasPermission("arparticles")) {
                    if($this->giveParticle(...$args)) {
                        $sender->sendMessage(TextFormat::BLUE . ' ' . $args[0] . ' has a new particle effect!');
                    } else {
                        $this->getServer()->broadcastMessage(TextFormat::BLUE . ' Unable to give ' . $args[0] . ' a new particle effect!');
                    }
                    return true;
                }

                $sender->sendMessage(TextFormat::RED . " You don't have permissions to do that...");
                return true;
            case "remove":
                if(count($args) < 1){
                    array_unshift($args, $sender->getDisplayName());
                }

                /**
                 * Check perms, then remove particles
                 */
                if ($sender->hasPermission("arparticles")) {
                    $args[] = true;
                    if($this->removeParticle(...$args)) {
                        $sender->sendMessage(TextFormat::RED . ' ' . $args[0] . '\'s particle effect was removed!');
                    } else {
                        $sender->sendMessage(TextFormat::RED . ' Unable to remove ' . $args[0] . '\'s particle effect!');
                    }
                    return true;
                }

                $sender->sendMessage(TextFormat::RED . " You don't have permissions to do that...");
                return true;
            case "help":
                $sender->sendMessage(TextFormat::GREEN . ' Available commands: give, remove');
                return true;
                break;
            default:
                return false;
        }
    }

    /**
     * Give a player particles if they are in the config
     *
     * @param PlayerLoginEvent $event The login event
     */
    public function PlayerLoginEventParticles(PlayerLoginEvent $event) {
        if (isset($this->players[$event->getPlayer()->getDisplayName()])) {
            $this->giveParticle($event->getPlayer()->getDisplayName(), $this->players[$event->getPlayer()->getDisplayName()]);
        }
    }

    /**
     * Remove the particles from a player when they leave
     *
     * @param PlayerQuitEvent $event The quit event
     */
    public function PlayerQuitEventParticles(PlayerQuitEvent $event) {
        if (isset($this->players[$event->getPlayer()->getDisplayName()])) {
            $this->removeParticle($event->getPlayer()->getDisplayName());
        }
    }

    /**
     * Give a player particles when they respawn
     *
     * @param PlayerRespawnEvent $event The respawn event
     */
    public function PlayerRespawnEventParticles(PlayerRespawnEvent $event) {
        if (isset($this->players[$event->getPlayer()->getDisplayName()])) {
            $this->giveParticle($event->getPlayer()->getDisplayName(), $this->players[$event->getPlayer()->getDisplayName()]);
        }
    }

    /**
     * Give a user particles
     *
     * @param  string $user     The username of the person to give particles
     * @param  string $particle The particle effect to give (The class name)
     * @return boolean          Whether or not giving the particles was successful
     */
    public function giveParticle($user = '', $particle = '') {
        if(($player = $this->getServer()->getPlayerExact($user)) instanceof Player) {
            if(!isset($this->particles[$player->getDisplayName()])) {
                $name = $this->getParticleClass($particle);
                $this->particles[$player->getDisplayName()] = $this->manager->setPlayerParticleEffect($player, $this->manager::$$name);
                $this->players[$player->getDisplayName()] = get_class($this->particles[$player->getDisplayName()]);
                return true;
            }
        }
        return false;
    }

    /**
     * Remove the particles from the user
     *
     * @param  string $user  The username of the person to take the particles from
     * @param  boolean $unset Whether or not to unset the user from the config
     * @return boolean        Whether or not the command was successful
     */
    public function removeParticle($user = '', $unset = false) {
        if(($player = $this->getServer()->getPlayerExact($user)) instanceof Player) {
            if(isset($this->particles[$player->getDisplayName()])) {
                unset($this->particles[$player->getDisplayName()]);
                $this->manager->removeEffect($player);
                if($unset) {
                    unset($this->players[$player->getDisplayName()]);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Get the particle class for the manager
     * @param  string $particle The particle class
     * @return string           The particle
     */
    public function getParticleClass($particle) {
        $path = explode('\\', $particle);
        $particle = array_pop($path);
        switch($particle) {
            case 'LavaParticleEffect':
                $var = 'lava';
                break;
            case 'PortalParticleEffect':
                $var = 'portal';
                break;
            case 'RainbowParticleEffect':
                $var = 'rainbow';
                break;
            case 'RedstoneParticleEffect':
                $var = 'redstone';
                break;
            default:
                $var = 'portal';
                break;
        }
        return $var;
    }

///START OF SIMPLE CUSTOM PLAYERS///
/*Making Config For MaxHP And Hunger When Player Join And Die*/
/*
  public function testonQuit(PlayerQuitEvent $event) {
    $p = $event->getPlayer ();
    $this->data = new Config ($this->getDataFolder () . "plugins/ARCore/Player/" . $p->getName () . ".yml");
    unlink ($this->data);
  }

  public function testonjoin(PlayerJoinEvent $event) {
    $p = $event->getPlayer ();
    @mkdir($this->getDataFolder () . "plugins/ARCore/Player/");
    @file_put_contents ($this->getDataFolder () . "plugins/ARCore/Player/" . $p->getName () . ".yml", yaml_emit([
    "Health" => 40,
    "MaxHealth" => 40
    ]));
  }*/
//DONE!
/*Plugin OnJoin*/
   public function onJoiningPlayerSettings(PlayerJoinEvent $event){ 
       $event->setJoinMessage("");
       $player = $event->getPlayer(); 
       $player->setFood($this->custom->get("SetPlayerFoodBarOnJoin"));
       $player->setMaxHealth($this->custom->get("SetMaxPlayerHealthOnJoin"));
       $player->setHealth($this->custom->get("SetPlayerHealthOnJoin"));
       $player->setMovementSpeed(0.12);//DO NOT MESS WITH THIS!!
       $player->setGamemode(0);//Forgot To Set A Player Gamemode To Survival??LOL NOW YOU WONT FORGOT!!
       //under here is non stable code 
       $positionx = $this->getServer()->getDefaultLevel()->getSafeSpawn()->getX();
       $positiony = $this->getServer()->getDefaultLevel()->getSafeSpawn()->getY()+1.3;
       $positionz = $this->getServer()->getDefaultLevel()->getSafeSpawn()->getZ();
       $worldlevel = $this->getServer()->getDefaultLevel();
       $player->setLevel($worldlevel);
       $player->teleport(new Vector3($positionx, $positiony, $positionz, $worldlevel));
       $player->setRotation(270, 0);//DAFUQ TO USE THIS??!!
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
       $player->setFood($this->custom->get("SetPlayerFoodBarOnRespawn"));
       $player->setMaxHealth($this->custom->get("SetMaxPlayerHealthOnRespawn"));
       $player->setHealth($this->custom->get("SetPlayerHealthOnRespawn"));
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
        $killer->getInventory()->addItem(Item::get($this->custom->get("DropDeath")));//388
    }
  }
}

    public function ByeVoidz(PlayerMoveEvent $event){
        if($event->getTo()->getFloorY() =< 7){//lucky 7
			$player = $event->getPlayer();
			$x = $this->getServer()->getDefaultLevel()->getSafeSpawn()->getX();
			$y = $this->getServer()->getDefaultLevel()->getSafeSpawn()-> getY()+1.3;
			$z = $this->getServer()->getDefaultLevel()->getSafeSpawn()->getZ();
			$level = $this->getServer()->getDefaultLevel();
			$player->setLevel($level);
            $player = $event->getPlayer();
            $player->setMaxHealth($this->custom->get("NoVoid-SetPlayerMaxHealth"));
            $player->setHealth($this->custom->get("NoVoid-SetPlayerHealth"));
            $player->setFood($this->custom->get("NoVoid-SetPlayerFood"));
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
						$PlayerKiller = $this->custom->get("Player-Gain-Coins-PerKill");
						$PlayerKilled = $this->custom->get("Player-Lose-Coins-PerDeath");
						$damager->sendTip($this->custom->get("Player-Gains-Coins-For-Killing-Message"));
						$player->sendTip($this->custom->get("Player-Lose-Coins-For-Dying-Message"));
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
  public function onRun($tick) {
    foreach($this->getServer()->getOnlinePlayers() as $player) {
      if($player->hasPermission("hud.information")) {
        $player->sendTip("\n                                                             §7| §6"."§f". $player->getName()."\n                                                             §7| §e". $this->api->myMoney($player->getName())." Coins"."\n                                                             §7| §3". $this->getPlayerFaction($player->getName()) ."\n\n\n\n\n\n\n\n\n\n\n\n\n");
        } else {
          $player->sendPopup("§5ERROR");
          }//. UserDataManager::getGroup($player)
       }//. EconomyAPI::myMoney($player->getName()
//. EconomyAPI::myMoney($player->getName()
     }

///ENDS OF SIMPLE CUSTOM PLAYERS///

///START OF ENCHANTS ///
/*/
 *          [TODO]
 *
 *   Add Custom Enchantments
 *   ReCalculate The Enchantment Code
 *   Adds::Implement In Pets Custom Enchantments
 *
/*/
//DO NOT CHANGE THE swordDamages.php name!!
    public function calculateEndDamage($damage, $reduction){
    return $damage - $reduction;
    }

    public function calculateDamage($type, $material, $sharpness){
        $type = strtoupper($type);
        $damage = swordDamages::DAMAGE_VALUES;
        $damage = $damage[$type];
        $material = strtoupper($material);
        $plus = swordDamages::MATERIAL_VALUES;
        $plus = $plus[$material];
        if ($damage > 1)
            $damage += $plus;
            $damage /= 2;
            $damage += .625 * $sharpness;
        return $damage;
    }

    public function onArrowShoot(EntityInventoryChangeEvent $event){
        $player = $event->getEntity();
        if ($player instanceof Player){
            if($event->getOldItem()->getId() === Item::ARROW){
                if($player->getInventory()->getItemInHand()->getId() === Item::BOW){
                    $infinty = $player->getInventory()->getItemInHand()->getEnchantment(22);
                    if($infinty !== null){
                        $event->setCancelled(true);
                    }
                }
            }
        }
    }

    public function onArrowHit(ProjectileHitEvent $event){
        $arrow = $event->getEntity();
        if ($arrow instanceof Arrow) {
            $player = $arrow->shootingEntity;
            if ($player instanceof Player) {
                if ($player->getInventory()->getItemInHand()->getId() === Item::BOW) {
                    $flame = $player->getInventory()->getItemInHand()->getEnchantment(21);
                    if ($flame !== null) {
                        foreach ($arrow->getLevel()->getEntities() as $entity) {
                            if ($entity->distance($arrow) < 1.0) {
                                $time = $this->calculateFireAspect($flame->getLevel());
                                $entity->setOnFire($time);
                            }
                        }
                    }
                    $infinty = $player->getInventory()->getItemInHand()->getEnchantment(22);
                    if ($infinty !== null) {
                        $arrow->despawnFromAll();
                    }
                }
            }
        }
    }

    public function onArrow(EntityDamageEvent $event){
        $player = $event->getEntity();
        if ($event instanceof EntityDamageByEntityEvent) {
            $damager = $event->getDamager();
            if ($damager instanceof Arrow) {
                $shooter = $damager->shootingEntity;
                if ($shooter instanceof Player) {
                    $itemInHand = $shooter->getInventory()->getItemInHand();
                    $knockBack = $itemInHand->getEnchantment(20);
                    $knockBack2 = $itemInHand->getEnchantment(12);
                    if (($knockBack !== null) || ($knockBack2 !== null)) {
                        if ($player instanceof Player) {
                            $this->calculateKnockBack($player, $knockBack->getLevel(), $shooter);
                        }
                    }
                }
            }
        }
    }

    public function calculateArmorReduction($materials, $protections){
        $material_head = $materials[0];
        $protection_head = $protections[0];
        $material_chest = $materials[1];
        $protection_chest = $protections[1];
        $material_leggings = $materials[2];
        $protection_leggings = $protections[2];
        $material_boots = $materials[3];
        $protection_boots = $protections[3];

        $def_head = armorReductions::MATERIAL_VALUES_BOOTS;
        $def_head = $def_head[$material_head];
        $def_chest = armorReductions::MATERIAL_VALUES_CHEST;
        $def_chest = $def_chest[$material_chest];
        $def_leggings = armorReductions::MATERIAL_VALUES_LEGGINGS;
        $def_leggings = $def_leggings[$material_leggings];
        $def_boots = armorReductions::MATERIAL_VALUES_BOOTS;
        $def_boots = $def_boots[$material_boots];

        $defensePoints = $def_head + $def_chest + $def_leggings + $def_boots;

        $epf = 0;
        if ($protection_head > 0) $epf += floor((6 + $protection_head * $protection_head) / 4);
        if ($protection_chest > 0) $epf += floor((6 + $protection_chest * $protection_chest) / 4);
        if ($protection_leggings > 0) $epf += floor((6 + $protection_leggings * $protection_leggings) / 4);
        if ($protection_boots > 0) $epf += floor((6 + $protection_boots * $protection_boots) / 4);

        $epf = min(ceil(min($epf, 25) * .75), 20);

        $reduction = 1 - (1 - .04 * $defensePoints) * (1 - .04 * $epf);

        return $reduction;
    }

    public function onDamage(EntityDamageEvent $event){
        $player = $event->getEntity();
        if ($player instanceof Player) {
            if ($event instanceof EntityDamageByEntityEvent) {
                $damager = $event->getDamager();
                if ($damager instanceof Player) {
                    $itemInHand = $damager->getInventory()->getItemInHand();
                    $head = $player->getInventory()->getHelmet();
                    $chest = $player->getInventory()->getChestplate();
                    $leggings = $player->getInventory()->getLeggings();
                    $boots = $player->getInventory()->getBoots();

                    $itemInHand_type = $this->getWeaponType($itemInHand->getId());
                    $itemInHand_material = $this->getWeaponMaterial($itemInHand->getId());
                    $sharpness = 0;
                    foreach ($itemInHand->getEnchantments() as $enchantment) {
                        if ($enchantment->getId() === 9) {
                            $sharpness += $enchantment->getLevel();
                        }
                    }

                    $damage = $this->calculateDamage($itemInHand_type, $itemInHand_material, $sharpness);
                    $materials = array(
                        $this->getArmorMaterial($player->getInventory()->getHelmet()->getId()),
                        $this->getArmorMaterial($player->getInventory()->getChestplate()->getId()),
                        $this->getArmorMaterial($player->getInventory()->getLeggings()->getId()),
                        $this->getArmorMaterial($player->getInventory()->getBoots()->getId()),
                    );
                    $prot_head = 0;
                    $prot_chest = 0;
                    $prot_leggings = 0;
                    $prot_boots = 0;
                    foreach ($player->getInventory()->getHelmet()->getEnchantments() as $enchantment) {
                        if ($enchantment->getId() === 0) {
                            $prot_head += $enchantment->getLevel();
                        }
                    }
                    foreach ($player->getInventory()->getChestplate()->getEnchantments() as $enchantment) {
                        if ($enchantment->getId() === 0) {
                            $prot_chest += $enchantment->getLevel();
                        }
                    }
                    foreach ($player->getInventory()->getLeggings()->getEnchantments() as $enchantment) {
                        if ($enchantment->getId() === 0) {
                            $prot_leggings += $enchantment->getLevel();
                        }
                    }
                    foreach ($player->getInventory()->getBoots()->getEnchantments() as $enchantment) {
                        if ($enchantment->getId() === 0) {
                            $prot_boots += $enchantment->getLevel();
                        }
                    }
                    $protections = array(
                        $prot_head,
                        $prot_chest,
                        $prot_leggings,
                        $prot_boots
                    );
                    $reduction = $this->calculateArmorReduction($materials, $protections);

                    $endDamage = $this->calculateEndDamage($damage, $reduction);
                    $event->setDamage($endDamage);
                    $fireAspect = $itemInHand->getEnchantment(13);
                    if ($fireAspect !== null) {
                        $f_a = $this->calculateFireAspect($fireAspect->getLevel());
                        if ($f_a) {
                            $player->setOnFire($f_a);
                        }
                    }
                    $knockBack = $itemInHand->getEnchantment(12);
                    if ($knockBack !== null) {
                        if ($player instanceof Player) {
                            $this->calculateKnockBack($player, $knockBack->getLevel(), $damager);
                        }
                    }
                }
            }
        }
    }

    public function calculateFireAspect($level){
        $bool = false;
        switch ($level) {
            case 1:
                $rand = mt_rand(1, 3);
                if ($rand === 1) $bool = true;
                break;
            case 2:
                $rand = mt_rand(1, 2);
                if ($rand === 1) $bool = true;
                break;
            default:
                $bool = true;
                break;
        }
        if ($bool) {
            $time = (($level * 2) + 1.5);
            return $time;
        } else {
            return false;
        }
    }

    public function calculateKnockBack(Player $player, $level, Player $damager){
        switch ($level) {
            case 1:
                $level = $level + 0.5;
                break;
        }
        if ($damager->getDirection() == 0) {
            $player->knockBack($player, 0, 1, 0, $level);
        } elseif ($damager->getDirection() == 1) {
            $player->knockBack($player, 0, 0, 1, $level);
        } elseif ($damager->getDirection() == 2) {
            $player->knockBack($player, 0, -1, 0, $level);
        } elseif ($damager->getDirection() == 3) {
            $player->knockBack($player, 0, 0, -1, $level);
        }
    }

    public function getWeaponMaterial($id){
        $wood = array(268, 269, 270, 271);
        $gold = array(283, 284, 285, 286);
        $stone = array(272, 273, 274, 275);
        $iron = array(267, 256, 257, 258);
        $diamond = array(276, 277, 278, 279);
        if (in_array($id, $wood)) {
            return "WOOD";
        } elseif (in_array($id, $gold)) {
            return "GOLD";
        } elseif (in_array($id, $stone)) {
            return "STONE";
        } elseif (in_array($id, $iron)) {
            return "IRON";
        } elseif (in_array($id, $diamond)) {
            return "DIAMOND";
        }
        return "WOOD";
    }

    public function getWeaponType($id){
        $swords = array(267, 268, 272, 283, 276);
        $axes = array(258, 271, 275, 279, 286);
        $pickaxes = array(257, 270, 274, 278, 285);
        $shovels = array(256, 269, 273, 277, 284);
        if (in_array($id, $swords)) {
            return "SWORD";
        } elseif (in_array($id, $axes)) {
            return "AXE";
        } elseif (in_array($id, $pickaxes)) {
            return "PICKAXE";
        } elseif (in_array($id, $shovels)) {
            return "SHOVEL";
        }
        return "OTHER";
    }

    public function getArmorMaterial($id){
        $leather = array(298, 299, 300, 301);
        $chain = array(302, 303, 304, 305);
        $iron = array(306, 307, 308, 309);
        $diamond = array(310, 311, 312, 313);
        $gold = array(314, 315, 316, 317);
        if (in_array($id, $leather)) {
            return "LEATHER";
        } elseif (in_array($id, $chain)) {
            return "CHAIN";
        } elseif (in_array($id, $iron)) {
            return "IRON";
        } elseif (in_array($id, $diamond)) {
            return "DIAMOND";
        } elseif (in_array($id, $gold)) {
            return "GOLD";
        }
        return "LEATHER";
    }

    public function getArmorType($id){
        $head = array(298, 302, 306, 310, 314);
        $chest = array(299, 303, 307, 311, 315);
        $leggings = array(300, 304, 304, 312, 316);
        $boots = array(301, 305, 309, 313, 317);
        if (in_array($id, $head)) {
            return "HEAD";
        } elseif (in_array($id, $chest)) {
            return "CHEST";
        } elseif (in_array($id, $leggings)) {
            return "LEGGINGS";
        } elseif (in_array($id, $boots)) {
            return "BOOTS";
        }
        return "NONE";
    }
///ENDS OF ENCHANTS///

///START OF CLANS///
//Todo. Rewrite Anythings That Have factions to clan cause we want a clean code.
//Add Clans Wars(base on NeuroBinds Project Guilds Plugs)
		
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		$this->fCommand->onCommand($sender, $command, $label, $args);
	}
	public function isInFaction($player) {
		$player = strtolower($player);
		$result = $this->db->query("SELECT * FROM master WHERE player='$player';");
		$array = $result->fetchArray(SQLITE3_ASSOC);
		return empty($array) == false;
	}
    public function setFactionPower($faction,$power){ 
		$stmt = $this->db->prepare("INSERT OR REPLACE INTO strength (faction, power) VALUES (:faction, :power);");   
        $stmt->bindValue(":faction", $faction);
		$stmt->bindValue(":power", $power);
		$result = $stmt->execute();
    }
    public function setAllies($faction1, $faction2){
        $stmt = $this->db->prepare("INSERT INTO allies (faction1, faction2) VALUES (:faction1, :faction2);");  
        $stmt->bindValue(":faction1", $faction1);
		$stmt->bindValue(":faction2", $faction2);
		$result = $stmt->execute();
    }
    public function areAllies($faction1, $faction2){
        $result = $this->db->query("SELECT * FROM allies WHERE faction1 = '$faction1' AND faction2 = '$faction2';");
        $resultArr = $result->fetchArray(SQLITE3_ASSOC);
        if(empty($resultArr)==false){
            return true;
        } 
    } 
    public function deleteAllies($faction1, $faction2){
        $stmt = $this->db->prepare("DELETE FROM allies WHERE faction1 = '$faction1' AND faction2 = '$faction2';");   
		$result = $stmt->execute();
    }
    public function getFactionPower($faction){
        $result = $this->db->query("SELECT * FROM strength WHERE faction = '$faction';");
        $resultArr = $result->fetchArray(SQLITE3_ASSOC);
        return (int) $resultArr["power"];
    }
    public function addFactionPower($faction, $power){
		$stmt = $this->db->prepare("INSERT OR REPLACE INTO strength (faction, power) VALUES (:faction, :power);");   
        $stmt->bindValue(":faction", $faction);
		$stmt->bindValue(":power", $this->getFactionPower($faction) + $power);
		$result = $stmt->execute();
    }
    public function subtractFactionPower($faction,$power){
		$stmt = $this->db->prepare("INSERT OR REPLACE INTO strength (faction, power) VALUES (:faction, :power);");   
        $stmt->bindValue(":faction", $faction);
		$stmt->bindValue(":power", $this->getFactionPower($faction) - $power);
		$result = $stmt->execute();
    }
        
	public function isLeader($player) {
		$faction = $this->db->query("SELECT * FROM master WHERE player='$player';");
		$factionArray = $faction->fetchArray(SQLITE3_ASSOC);
		return $factionArray["rank"] == "Leader";
	}
	
	public function isOfficer($player) {
		$faction = $this->db->query("SELECT * FROM master WHERE player='$player';");
		$factionArray = $faction->fetchArray(SQLITE3_ASSOC);
		return $factionArray["rank"] == "Officer";
	}
	
	public function isMember($player) {
		$faction = $this->db->query("SELECT * FROM master WHERE player='$player';");
		$factionArray = $faction->fetchArray(SQLITE3_ASSOC);
		return $factionArray["rank"] == "Member";
	}
	
	public function getPlayerFaction($player) {
		$faction = $this->db->query("SELECT * FROM master WHERE player='$player';");
		$factionArray = $faction->fetchArray(SQLITE3_ASSOC);
		return $factionArray["faction"];
	}
	
	public function getLeader($faction) {
		$leader = $this->db->query("SELECT * FROM master WHERE faction='$faction' AND rank='Leader';");
		$leaderArray = $leader->fetchArray(SQLITE3_ASSOC);
		return $leaderArray['player'];
	}
	
	public function factionExists($faction) {
		$result = $this->db->query("SELECT * FROM master WHERE faction='$faction';");
		$array = $result->fetchArray(SQLITE3_ASSOC);
		return empty($array) == false;
	}
	
	public function sameFaction($player1, $player2) {
		$faction = $this->db->query("SELECT * FROM master WHERE player='$player1';");
		$player1Faction = $faction->fetchArray(SQLITE3_ASSOC);
		$faction = $this->db->query("SELECT * FROM master WHERE player='$player2';");
		$player2Faction = $faction->fetchArray(SQLITE3_ASSOC);
		return $player1Faction["faction"] == $player2Faction["faction"];
	}
	
	public function getNumberOfPlayers($faction) {
		$query = $this->db->query("SELECT COUNT(*) as count FROM master WHERE faction='$faction';");
		$number = $query->fetchArray();
		return $number['count'];
	}
	
	public function isFactionFull($faction) {
		return $this->getNumberOfPlayers($faction) >= $this->prefs->get("MaxPlayersPerFaction");
        
	}
	
	public function isNameBanned($name) {
		$bannedNames = explode(":", file_get_contents($this->getDataFolder() . "PlayerClanBanned.txt"));
		return in_array($name, $bannedNames);
	}
	
    public function newPlot($faction, $x1, $z1, $x2, $z2) {
		$stmt = $this->db->prepare("INSERT OR REPLACE INTO plots (faction, x1, z1, x2, z2) VALUES (:faction, :x1, :z1, :x2, :z2);");
		$stmt->bindValue(":faction", $faction);
		$stmt->bindValue(":x1", $x1);
		$stmt->bindValue(":z1", $z1);
		$stmt->bindValue(":x2", $x2);
		$stmt->bindValue(":z2", $z2);
		$result = $stmt->execute();
	}
	public function drawPlot($sender, $faction, $x, $y, $z, $level, $size) {
		$arm = ($size - 1) / 2;
		$block = new Snow();
		if($this->cornerIsInPlot($x + $arm, $z + $arm, $x - $arm, $z - $arm)) {
			$claimedBy = $this->factionFromPoint($x, $z);
            $power_claimedBy = $this->getFactionPower($claimedBy);
            $power_sender = $this->getFactionPower($faction);

            if($this->prefs->get("EnableOverClaim")){
                if($power_sender < $power_claimedBy){
                    $sender->sendMessage($this->formatMessage("This area is aleady claimed by $claimedBy with power $power_claimedBy. Your Clan has $power_sender power. You can not overclaim this plot."));
                } else {
                    $sender->sendMessage($this->formatMessage("§6- §3This area is aleady claimed by §e$claimedBy §3with power §b$power_claimedBy §3. Your Clan has $power_sender power. Type /c overclaim to overclaim this plot if you want."));
                }
                return false;
            } else {
			    $sender->sendMessage($this->formatMessage("§6- §3This area is aleady claimed by $claimedBy with power $power_claimedBy. §cOverclaiming is disabled."));
			    return false;
            }
		}
		$level->setBlock(new Vector3($x + $arm, $y, $z + $arm), $block);
		$level->setBlock(new Vector3($x - $arm, $y, $z - $arm), $block);
		$this->newPlot($faction, $x + $arm, $z + $arm, $x - $arm, $z - $arm);
		return true;
	}
	
	public function isInPlot($player) {
		$x = $player->getFloorX();
		$z = $player->getFloorZ();
		$result = $this->db->query("SELECT * FROM plots WHERE $x <= x1 AND $x >= x2 AND $z <= z1 AND $z >= z2;");
		$array = $result->fetchArray(SQLITE3_ASSOC);
		return empty($array) == false;
	}
   
	
	public function factionFromPoint($x,$z) {
		$result = $this->db->query("SELECT * FROM plots WHERE $x <= x1 AND $x >= x2 AND $z <= z1 AND $z >= z2;");
		$array = $result->fetchArray(SQLITE3_ASSOC);
		return $array["faction"];
	}
   
	
	public function inOwnPlot($player) {
		$playerName = $player->getName();
		$x = $player->getFloorX();
		$z = $player->getFloorZ();
		return $this->getPlayerFaction($playerName) == $this->factionFromPoint($x, $z);
	}
	
	public function pointIsInPlot($x,$z) {
		$result = $this->db->query("SELECT * FROM plots WHERE $x <= x1 AND $x >= x2 AND $z <= z1 AND $z >= z2;");
		$array = $result->fetchArray(SQLITE3_ASSOC);
		return !empty($array);
	}
	
	public function cornerIsInPlot($x1, $z1, $x2, $z2) {
		return($this->pointIsInPlot($x1, $z1) || $this->pointIsInPlot($x1, $z2) || $this->pointIsInPlot($x2, $z1) || $this->pointIsInPlot($x2, $z2));
	}
	
	public function formatMessage($string, $confirm = false) {
		if($confirm) {
			return "" . TextFormat::RED . "" . TextFormat::WHITE . "§l§b»§r" . TextFormat::GREEN . "$string";
		} else {	
			return "" . TextFormat::RED . "" . TextFormat::WHITE . "§l§b»§r " . TextFormat::RED . "$string";
		}
	}
	
	public function motdWaiting($player) {
		$stmt = $this->db->query("SELECT * FROM motdrcv WHERE player='$player';");
		$array = $stmt->fetchArray(SQLITE3_ASSOC);
		$this->getServer()->getLogger()->info("\$player = " . $player);
		return !empty($array);
	}
	
	public function getMOTDTime($player) {
		$stmt = $this->db->query("SELECT * FROM motdrcv WHERE player='$player';");
		$array = $stmt->fetchArray(SQLITE3_ASSOC);
		return $array['timestamp'];
	}
	
	public function setMOTD($faction, $player, $msg) {
		$stmt = $this->db->prepare("INSERT OR REPLACE INTO motd (faction, message) VALUES (:faction, :message);");
		$stmt->bindValue(":faction", $faction);
		$stmt->bindValue(":message", $msg);
		$result = $stmt->execute();
		
		$this->db->query("DELETE FROM motdrcv WHERE player='$player';");
	}
///ENDS OF EXTRAS CLANS ////

///START OF PETS ///
//Todo. Rewrite To Make The AI movement to be like in Vanilla.
/*
*Add AI pets message..
* Like OnJoin.
* Get To The Lobby Back.
* OnPets Change..
* OnPets Remove..
* OnPets Create..
//Note this is possible by sendMessage(); at every events lol.
*/
	/*
	public function create($player,$type, Position $source, ...$args) {
		$chunk = $source->getLevel()->getChunk($source->x >> 4, $source->z >> 4, true);
		$nbt = new CompoundTag("", [
			"Pos" => new ListTag("Pos", [
				new DoubleTag("", $source->x),
				new DoubleTag("", $source->y),
				new DoubleTag("", $source->z)
					]),
			"Motion" => new ListTag("Motion", [
				new DoubleTag("", 0),
				new DoubleTag("", 0),
				new DoubleTag("", 0)
					]),
			"Rotation" => new ListTag("Rotation", [
				new FloatTag("", $source instanceof Location ? $source->yaw : 0),
				new FloatTag("", $source instanceof Location ? $source->pitch : 0)
					]),
		]);
		$pet = Entity::createEntity($type, $chunk, $nbt, ...$args);
		$data = new Config($this->getDataFolder() . "PetPlayer/" . strtolower($player->getName()) . ".yml", Config::YAML);
		$data->set("type", $type); 
		$data->save();
		$pet->setOwner($player);
		$pet->spawnToAll();
		return $pet; 
	}
	public function createPet(Player $player, $type, $holdType = "") {
 		if (isset($this->pet[$player->getName()]) != true) {	
			$len = rand(8, 12); 
			$x = (-sin(deg2rad($player->yaw))) * $len  + $player->getX();
			$z = cos(deg2rad($player->yaw)) * $len  + $player->getZ();
			$y = $player->getLevel()->getHighestBlockAt($x, $z);
			$source = new Position($x , $y + 2.5, $z, $player->getLevel());
			if (isset(self::$type[$player->getName()])){
				$type = self::$type[$player->getName()];
			}
 			switch ($type){
 				case "WolfPet":
 				break;
 				case "RabbitPet":
 				break;
 				case "PigPet":
 				break;
 				case "SheepPet":
 				break;
 				case "OcelotPet":
 				break;
 				case "ChickenPet":
 				break;
 				case "BatPet":
 				break;
 				case "MagmaPet":
 				break;
 				case "SilverfishPet":
 				break;
 				case "BlockPet":
 				break;
 				default:
 					$pets = array("OcelotPet", "PigPet", "SheepPet", "WolfPet",  "RabbitPet", "ChickenPet", "BatPet", "MagmaPet", "BlockPet", "SilverfishPet");
 					$type = $pets[rand(0, 10)];
 			}
			$pet = $this->create($player,$type, $source);
			return $pet;
 		}
	}
	public function onPlayerQuit(PlayerQuitEvent $event) {
		$player = $event->getPlayer();
		$this->disablePet($player);
	}
	
	public function disablePet(Player $player) {
		if (isset(self::$pet[$player->getName()])) {
			self::$pet[$player->getName()]->close();
			self::$pet[$player->getName()] = null;
		}
	}
	
	public function changePet(Player $player, $newtype){
		$type = $newtype;
		$this->disablePet($player);
		self::$pet[$player->getName()] = $this->createPet($player, $newtype);
	}
	
	public function getPet($player) {
		return self::$pet[$player];
	}
	public function onJoinPets(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$data = new Config($this->getDataFolder() . "PetPlayer/" . strtolower($player->getName()) . ".yml", Config::YAML);
		if($data->exists("type")){ 
			$type = $data->get("type");
			$this->changePet($player, $type);
		}
		if($data->exists("name")){ 
			$name = $data->get("name");
			$this->getPet($player->getName())->setNameTag("§8"."§8$name");
		}
	}
    */
/// Ends Of Pets ////


///Inventory Saver START ///

//Known Bugs:
/*/
 * Works Only When Player On Survivals Mode (GM 1)
 * Creative Player Have Bugs
 *
/*/

    public function onLevelChange(EntityLevelChangeEvent $event){
        $ent = $event->getEntity();
        if($ent instanceof Player and $ent->hasPermission("saver.inventory.switch")){
            $this->saveInv($ent, $event->getOrigin());
            $ent->getInventory()->clearAll();
            $this->loadInv($ent, $event->getTarget());
        }
    }

    public function saveInv(Player $player, Level $from){
        $from = strtolower($from->getFolderName());
        $name = strtolower($player->getName());
        $contents = base64_encode(serialize($player->getInventory()->getContents()));
        $armor = base64_encode(serialize($player->getInventory()->getArmorContents()));

        $this->inventories->exec("CREATE TABLE IF NOT EXISTS `$from` (name TEXT PRIMARY KEY, slots BLOB, armor BLOB)");

        $stmt = $this->inventories->prepare("UPDATE `$from` SET slots = :slots, armor = :armor WHERE name = :name");
        $stmt->bindValue(":slots", $contents, SQLITE3_TEXT);
        $stmt->bindValue(":armor", $armor, SQLITE3_BLOB);
        $stmt->bindValue(":name", $name, SQLITE3_BLOB);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->inventories->prepare("INSERT OR IGNORE INTO `$from` (name, slots, armor) VALUES (:name, :slots, :armor)");
        $stmt->bindValue(":name", $name, SQLITE3_TEXT);
        $stmt->bindValue(":slots", $contents, SQLITE3_BLOB);
        $stmt->bindValue(":armor", $armor, SQLITE3_BLOB);
        $stmt->execute();
        $stmt->close();
    }

    public function loadInv(Player $player, Level $to){
        $to = strtolower($to->getFolderName());

        $this->inventories->exec("CREATE TABLE IF NOT EXISTS `$to` (name TEXT PRIMARY KEY, slots BLOB, armor BLOB)");

        $stmt = $this->inventories->prepare("SELECT * FROM `$to` WHERE name = :name");
        $stmt->bindValue(":name", strtolower($player->getName()), SQLITE3_TEXT);
        $query = $stmt->execute();
        if($query instanceof \SQLite3Result){
            $data = $query->fetchArray(SQLITE3_ASSOC);
            if(isset($data["slots"]) and isset($data["armor"])){
                $player->getInventory()->setContents(unserialize(base64_decode($data["slots"])));
                $player->getInventory()->setArmorContents(unserialize(base64_decode($data["armor"])));
            }
        }
        $query->finalize();
        $stmt->close();
    }

///Inventory Saver END///

//Any under here wait till i unbusy or you can start without me...

/*Still Finding A Way For A Good Way To Encrypt The Password
Note To Myself: Dont Use Multiplication Hash..*/
//I finding a way to make a security password without to worry any leaked..

//Update: Delay XBox Login
//Info: Code Taken From SimpleAuths(by Soghicp), ArchAuths(by GamerXzavier/HyGlobalHD) And Edited.

///Start Of Auths Code///

    public function getPlayer($player) {
        $player = strtolower($player);
        $statement = $this->auths->prepare("SELECT * FROM players WHERE name = :name");
        $statement->bindValue(":name", $player, SQLITE3_TEXT);
        $result = $statement->execute();
        if($result instanceof \SQLite3Result) {
            $data = $result->fetchArray(SQLITE3_ASSOC);
            $result->finalize();
            if(isset($data["name"])) {
                unset($data["name"]);
                $statement->close();
                return $data;
            }
        }
        $statement->close();
        return null;
    }

    public function updatePlayer(Player $player, $password, $pin, $uuid, $attempts) {
        $statement = $this->auths->prepare("UPDATE players SET pin = :pin, password = :password, uuid = :uuid, attempts = :attempts WHERE name = :name");
        $statement->bindValue(":name", strtolower($player->getName()), SQLITE3_TEXT);
        $statement->bindValue(":password", $password, SQLITE3_TEXT);
        $statement->bindValue(":pin", $pin, SQLITE3_INTEGER);
        $statement->bindValue(":uuid", $uuid, SQLITE3_INTEGER);
        $statement->bindValue(":attempts", $attempts, SQLITE3_INTEGER);
        $statement->execute();
    }

    public function getPin(Player $player) {
        $data = $this->getPlayer($player->getName());
        if(!is_null($data)) {
            if(!isset($data["pin"])) {
                $pin = mt_rand(1000, 9999); //If you use $this->generatePin(), there will be issues!
                $this->updatePlayer($player, $pin, $this->getPassword($player), $this->getUUID($player), $this->getAttempts($player));
                return $pin;
            }
            return $data["pin"];
        }
        return null;
    }

    public function getPassword(Player $player) { //ENCRYPTED!
        $data = $this->getPlayer($player->getName());
        if(!is_null($data)) {
            return $data["password"];
        }
        return null;
    }

    public function getUUID(Player $player) {
        $data = $this->getPlayer($player->getName());
        if(!is_null($data)) {
            return $data["uuid"];
        }
        return null;
    }

    public function getAttempts(Player $player) {
        $data = $this->getPlayer($player->getName());
        if(!is_null($data)) {
            if(!isset($data["attempts"])) {
                $this->updatePlayer($player, $this->getPin($player), $this->getPassword($player), $this->getUUID($player), 0);
                return 0;
            }
            return $data["attempts"];
        }
        return null;
    }

    public function generatePin(Player $player) {
        $newpin = mt_rand(1000, 9999);
        if($this->isCorrectPin($player, $newpin)){
            return $this->generatePin($player);
        }
        return $newpin;
    }

    public function isCorrectPassword(Player $player, $password) {
        if(password_verify($password, $this->getPassword($player))) {
            return true;
        }
        return false;
    }

    public function isCorrectPin(Player $player, $pin) {
        if($pin == $this->getPin($player)) {
            return true;
        }
        return false;
    }

    public function isAuthenticated(Player $player) {
        if(isset($this->authenticated[strtolower($player->getName())])) return true;
        return false;
    }

    public function isRegistered($player) {
        return $this->getPlayer(strtolower($player)) !== null;
    }

    public function login(Player $player, $password) {
        if($this->isAuthenticated($player)) {
            $player->sendMessage($this->auth->get("already-authenticated"));
            return false;
        }
        if(!$this->isRegistered($player->getName())) {
            $player->sendMessage($this->auth->get("not-registered"));
            return false;
        }
        if(!$this->isCorrectPassword($player, $password)) {
            if(isset($this->tries[strtolower($player->getName())])) {
                $this->tries[strtolower($player->getName())]++;
                if($this->tries[strtolower($player->getName())] >= $this->auth->get("tries")) {
                    $this->updatePlayer($player, $this->getPassword($player), $this->getPin($player), $this->getUUID($player), $this->getAttempts($player) + 1);
                    $player->kick($this->auth->get("too-many-tries"));
                    return false;
                }
            } else {
                $this->tries[strtolower($player->getName())] = 1;
            }
            $tries = $this->auth->get("tries") - $this->tries[strtolower($player->getName())];
            $player->sendMessage(str_replace("{tries}", $tries, $this->auth->get("incorrect-password")));
            return false;
        }
        $this->force($player);
        return true;
    }

    public function force(Player $player, $login = true) {
        if(isset($this->messagetick[strtolower($player->getName())])) {
            unset($this->messagetick[strtolower($player->getName())]);
        }
        if(isset($this->tries[strtolower($player->getName())])) {
            unset($this->tries[strtolower($player->getName())]);
        }
        $this->authenticated[strtolower($player->getName())] = true;
        if($this->auth->get("invisible")) {
            $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
            $player->setDataProperty(Entity::DATA_SHOW_NAMETAG, Entity::DATA_TYPE_BYTE, 1);
        }
        if($this->auth->get("blindness")) {
            $player->removeEffect(15);
            $player->removeEffect(16);
        }
        if($login) {
            $player->sendMessage(str_replace("{attempts}", $this->getAttempts($player), $this->auth->get("authentication-success")));
        } else {
            $player->sendMessage(str_replace("{pin}", $this->getPin($player), $this->auth->get("register-success")));
        }
        $this->updatePlayer($player, $this->getPassword($player), $this->getPin($player), $player->getUniqueId()->toString(), 0);
        return true;
    }

    public function register(Player $player, $password, $confirmpassword) {
        if($this->isRegistered($player->getName())) {
            $player->sendMessage($this->auth->get("already-registered"));
            return false;
        }
        if(strlen($password) < $this->auth->get("minimum-password-length")) {
            $player->sendMessage($this->auth->get("password-too-short"));
            return false;
        }
        if($password !== $confirmpassword) {
            $player->sendMessage($this->auth->get("password-not-match"));
            return false;
        }
        $statement = $this->auths->prepare("INSERT INTO players (name, password, pin, uuid, attempts) VALUES (:name, :password, :pin, :uuid, :attempts)");
        $statement->bindValue(":name", strtolower($player->getName()), SQLITE3_TEXT);
        $statement->bindValue(":password", password_hash($password, PASSWORD_BCRYPT), SQLITE3_TEXT);
        $statement->bindValue(":pin", $this->generatePin($player), SQLITE3_INTEGER);
        $statement->bindValue(":uuid", $player->getUniqueId()->toString(), SQLITE3_INTEGER);
        $statement->bindValue(":attempts", 0, SQLITE3_INTEGER);
        $statement->execute();
        $this->force($player, false);
        return true;
    }

    public function changepassword(Player $player, $oldpassword, $newpassword) {
        if(!$this->isRegistered($player->getName())) {
            $player->sendMessage($this->auth->get("not-registered"));
            return false;
        }
        if(!$this->isCorrectPassword($player, $oldpassword)) {
            $player->sendMessage($this->auth->get("incorrect-password"));
            return false;
        }
        $pin = $this->generatePin($player);
        $this->updatePlayer($player, password_hash($newpassword, PASSWORD_BCRYPT), $newpin, $player->getUniqueId()->toString(), 0);
        $player->sendMessage($this->auth->get("password-change-success"));
        return true;
    }

    public function forgotpassword(Player $player, $pin, $newpassword) {
        if(!$this->isRegistered($player->getName())) {
            $player->sendMessage($this->auth->get("not-registered"));
            return false;
        }
        if($this->isAuthenticated($player)) {
            $player->sendMessage($this->auth->get("already-authenticated"));
            return false;
        }
        if(!$this->isCorrectPin($player, $pin)) {
            $player->sendMessage($this->auth->get("incorrect-pin"));
            return false;
        }
        $newpin = $this->generatePin($player);
        $this->updatePlayer($player, password_hash($newpassword, PASSWORD_BCRYPT), $newpin, $this->getUUID($player), $this->getPlayer($player)["attempts"]);
        $player->sendMessage(str_replace("{pin}", $newpin, $this->auth->get("forgot-password-success")));
    }

    public function resetpassword($player, $sender) {
        $player = strtolower($player);
        if($this->isRegistered($player)) {
            $statement = $this->auths->prepare("DELETE FROM players WHERE name = :name");
            $statement->bindValue(":name", $player, SQLITE3_TEXT);
            $statement->execute();
            if(isset($this->authenticated[$player])) {
                unset($this->authenticated[$player]);
            }
            $sender->sendMessage($this->auth->get("password-reset-success"));
            return true;
        }
        $sender->sendMessage($this->auth->get("not-registered-two"));
        return false;
    }

    public function logout(Player $player, $quit = true) {
        if($this->isAuthenticated($player)) {
            unset($this->authenticated[strtolower($player->getName())]);
            if(!$quit) {
                $this->messagetick[strtolower($player->getName())] = 5;
                $this->getServer()->getScheduler()->scheduleDelayedTask(new TimeoutTask($this, $player), $this->auth->get("timeout") * 20);
            }
        } else {
            if(isset($this->confirmPassword[strtolower($player->getName())])) {
                unset($this->confirmPassword[strtolower($player->getName())]);
            }
            if(isset($this->messagetick[strtolower($player->getName())])) {
                unset($this->messagetick[strtolower($player->getName())]);
            }
            if(isset($this->tries[strtolower($player->getName())])) {
                unset($this->tries[strtolower($player->getName())]);
            }
        }
    }

///Ends Of Auths Code///


///STARTS OF SKILLS///
/*Im gonna start with simple thing*/
//DELAY //
///ENDS OF SKILLS///
	//PETS
	public function create($player,$type, Position $source, ...$args) {
		$chunk = $source->getLevel()->getChunk($source->x >> 4, $source->z >> 4, true);
		$nbt = new CompoundTag("", [
			"Pos" => new ListTag("Pos", [
				new DoubleTag("", $source->x),
				new DoubleTag("", $source->y),
				new DoubleTag("", $source->z)
					]),
			"Motion" => new ListTag("Motion", [
				new DoubleTag("", 0),
				new DoubleTag("", 0),
				new DoubleTag("", 0)
					]),
			"Rotation" => new ListTag("Rotation", [
				new FloatTag("", $source instanceof Location ? $source->yaw : 0),
				new FloatTag("", $source instanceof Location ? $source->pitch : 0)
					]),
		]);
		$pet = Entity::createEntity($type, $chunk, $nbt, ...$args);
		$data = new Config($this->getDataFolder() . "PetPlayer/" . strtolower($player->getName()) . ".yml", Config::YAML);
		$data->set("type", $type); 
        $data->save();
		$pet->setOwner($player);
		$pet->spawnToAll();
        $pet->setNameTag(TF::BLUE."".$player->getName()."'s Pet");
		return $pet; 
	}

	public function createPet(Player $player, $type, $holdType = "") {
 		if (isset($this->pet[$player->getName()]) != true) {	
			$len = rand(8, 12); 
			$x = (-sin(deg2rad($player->yaw))) * $len  + $player->getX();
			$z = cos(deg2rad($player->yaw)) * $len  + $player->getZ();
			$y = $player->getLevel()->getHighestBlockAt($x, $z);

			$source = new Position($x , $y + 2, $z, $player->getLevel());
			if (isset(self::$type[$player->getName()])){
				$type = self::$type[$player->getName()];
			}
 			switch ($type){
 				case "WolfPet":
 				break;
 				case "ChickenPet":
 				break;
 				case "PigPet":
 				break;
 				case "BlazePet":
 				break;
 				case "MagmaPet":
				break;
 				case "RabbitPet":
				break;
 				case "BatPet":
				break;
 				case "SilverfishPet":
 				break;
 				case "SpiderPet":
 				break;
 				case "CowPet":
 				break;
 				case "CreeperPet":
 				break;
 				case "IronGolemPet":
 				break;
                case "HuskPet":
 				break;
                case "EndermanPet":
 				break;
 				case "SheepPet":
 				break;
 				case "WitchPet":
 				break;
 				case "BlockPet":
 				break;
 				default:
 					$pets = array("ChickenPet", "PigPet", "WolfPet", "BlazePet", "RabbitPet", "BatPet","SilverfishPet","SpiderPet","CowPet","CreeperPet","IronGolemPet","HuskPet","EndermanPet","SheepPet","WitchPet","BlockPet");
 					$type = $pets[rand(0, 3)];//Between Chicken And Wolf
 			}
			$pet = $this->create($player,$type, $source);
			return $pet;
 		}
	}

	public function onPlayerPetsQuit(PlayerQuitEvent $event) {
		$player = $event->getPlayer();
			$this->disablePet($player);
	}
	
	/**
	 * Get last damager name if it's another player
	 * 
	 * @param PlayerDeathEvent $event
	 */
	public function onPlayerPetsDeath(PlayerDeathEvent $event) {
		$player = $event->getEntity();
		$attackerEvent = $player->getLastDamageCause();
		if ($attackerEvent instanceof EntityDamageByEntityEvent) {
			$attacker = $attackerEvent->getDamager();
			if ($attacker instanceof Player) {
				$player->setLastDamager($attacker->getName());
			}
		}
	}

	//new Pets API By BalAnce cause LIFEBOAT's WAS SHIT!
	//still probably buggy idk worked fine for me
	
	public function togglePet(Player $player){
		if (isset(self::$pet[$player->getName()])){
			self::$pet[$player->getName()]->close();
			unset(self::$pet[$player->getName()]);
			$this->disablePet($player);
                        $player->sendMessage("Pet Disapeared");
				
			return;
		}
		self::$pet[$player->getName()] = $this->createPet($player, "");
		$player->sendMessage("Enabled Pet!");
	}
	
	public function disablePet(Player $player){
		if (isset(self::$pet[$player->getName()])){
			self::$pet[$player->getName()]->fastClose();
			unset(self::$pet[$player->getName()]);
		}
	}
	
	public function changePet(Player $player, $newtype){
		$type = $newtype;
		$this->disablePet($player);
		self::$pet[$player->getName()] = $this->createPet($player, $newtype);
	}
	
	public function getPet($player) {
		return self::$pet[$player];
	}
	public function onJoinPets(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$data = new Config($this->getDataFolder() . "PetPlayer/" . strtolower($player->getName()) . ".yml", Config::YAML);
		if($data->exists("type")){ 
			$type = $data->get("type");
			$this->changePet($player, $type);
		}
		if($data->exists("name")){ 
			$name = $data->get("name");
			$this->getPet($player->getName())->setNameTag("§8".$player->getName() ."'Pets");
		}
	}
	//PETS

}
?>
