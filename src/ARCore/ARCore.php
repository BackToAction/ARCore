<?php

namespace ARCore;
/*/
 * Copyrights Of NeuroBinds Project Corps.
 *
 * You May Edit,Sell,Share And Contribute.
 *
 * Somehow I Hate This.
 *
/*/

//player
use pocketmine\Player;
//inventory
use pocketmine\inventory\Inventory;
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\DoubleChestInventory;
//events
use pocketmine\event\entity\EntityInventoryChangeEvent;
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

class ARCore extends PluginBase implements Listener{
	
	public $db;
	public $prefs;

/*Plugins OnEnable*/
   public function onEnable(){		
//implementing clans!	
		@mkdir($this->getDataFolder());
		
		if(!file_exists($this->getDataFolder() . "BannedNames.txt")) {
			$file = fopen($this->getDataFolder() . "BannedNames.txt", "w");
			$txt = "Admin:admin:Staff:staff:Owner:owner:Builder:builder:Op:OP:op";
			fwrite($file, $txt);
		}
//load level/world
        $this->getServer()->loadLevel("ArchLobby");
/*Server Name*/
		$this->getServer()->getNetwork()->setName(TextFormat::GREEN . "Arch" . TextFormat::GOLD . "RPG " . TextFormat::RED . "Online" . TextFormat::WHITE . "\n" . TextFormat::BLACK . "Beta§l§8»v0.1 ");

       $this->getServer()->getPluginManager()->registerEvents($this ,$this);

       $this->getLogger()->info("Loading...");
       $this->getLogger()->info("Enabling...");
       $this->getLogger()->info("Enabled...");
	//trying to implement clans!
		$this->getServer()->getPluginManager()->registerEvents(new FactionListener($this), $this);
		$this->fCommand = new FactionCommands($this);
		
		$this->prefs = new Config($this->getDataFolder() . "FactionOptions.yml", CONFIG::YAML, array(
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
			}

   
/*Plugins OnDisable*/
   public function onDisable(){
       $this->getLogger()->info("Loading...");
       $this->getLogger()->info("Disabling...");
       $this->getLogger()->info("Disabled...");    
		$this->db->close();
	
   }
/*Plugin OnJoin*/
   public function onJoin(PlayerJoinEvent $event){ 
       $player = $event->getPlayer(); 
       $player->setFood(20);
       $player->setHealth(10);
   }
/*Plugins PRE*/
    public function PRE(PlayerRespawnEvent $event){
       $player = $event->getPlayer(); 
       $player->setFood(20);
       $player->setHealth(10);
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
    public function calculateEndDamage($damage, $reduction)
    {
        return $damage - $reduction;
    }

    public function calculateDamage($type, $material, $sharpness)
    {
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

    public function onArrowShoot(EntityInventoryChangeEvent $event)
    {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            if ($event->getOldItem()->getId() === Item::ARROW) {
                if ($player->getInventory()->getItemInHand()->getId() === Item::BOW) {
                    $infinty = $player->getInventory()->getItemInHand()->getEnchantment(22);
                    if ($infinty !== null) {
                        $event->setCancelled(true);
                    }
                }
            }
        }
    }

    public function onArrowHit(ProjectileHitEvent $event)
    {
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

    public function onArrow(EntityDamageEvent $event)
    {
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

    public function calculateArmorReduction($materials, $protections)
    {
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

    public function onDamage(EntityDamageEvent $event)
    {
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

    public function calculateFireAspect($level)
    {
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

    public function calculateKnockBack(Player $player, $level, Player $damager)
    {
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

    public function getWeaponMaterial($id)
    {
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

    public function getWeaponType($id)
    {
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

    public function getArmorMaterial($id)
    {
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

    public function getArmorType($id)
    {
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
//trying to implement clans!

		
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
		$bannedNames = explode(":", file_get_contents($this->getDataFolder() . "BannedNames.txt"));
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
			return "" . TextFormat::RED . "•RED Clan•" . TextFormat::WHITE . "•" . TextFormat::GREEN . "$string";
		} else {	
			return "" . TextFormat::RED . "•RED Clan•" . TextFormat::WHITE . "•" . TextFormat::RED . "$string";
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



}

?>
