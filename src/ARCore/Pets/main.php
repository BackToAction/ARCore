<?php

namespace pets;

use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Server;
use pets\command\PetCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as TF;

class main extends PluginBase implements Listener {
	
	public static $pet;
	public static $petState;
	public $petType;
	public $wishPet;
	public static $isPetChanging;
	public static $type;
	public function onEnable() {
		$server = Server::getInstance();
		$server->getCommandMap()->register('pets', new PetCommand($this,"pets"));
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
		//$server->getScheduler()->scheduleRepeatingTask(new task\PetsTick($this), 20*60);//run each minute for random pet messages
		//$server->getScheduler()->scheduleRepeatingTask(new task\SpawnPetsTick($this), 20);
		
	}

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
 					$type = $pets[rand(0, 3)];
 			}
			$pet = $this->create($player,$type, $source);
			return $pet;
 		}
	}

	public function onPlayerQuit(PlayerQuitEvent $event) {
		$player = $event->getPlayer();
		$pet = $player->getPet();
		if (!is_null($pet)) {
			$this->disablePet($player);
		}
	}
	
	/**
	 * Get last damager name if it's another player
	 * 
	 * @param PlayerDeathEvent $event
	 */
	public function onPlayerDeath(PlayerDeathEvent $event) {
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
	
// 	public function getPetState($player){
// 		if(isset(self::$petState[$player]['state'])) {
// 			if(self::$petState[$player]['delay'] > 0){
// 				self::$petState[$player]['delay']--;
// 				return false;
// 			}
// 			return self::$petState[$player];
// 		}
// 		return false;
// 	}
	
// 	public static function setPetState($state,$player, $petType = "", $delay = 2) {
// 		self::$petState[$player] = array(
// 				'state' => $state,
// 				'petType' => $petType,
// 				'delay' => $delay
// 		);
// 	}
}
