<?php
namespace ARCore\Pets;
use pocketmine\entity\Creature;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Timings;
use pocketmine\level\Level;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\math\Math;
use pocketmine\block\Air;
use pocketmine\block\Liquid;
use pocketmine\utils\TextFormat;
use Pets\main;
use ARCore\ARCore;
abstract class Pets extends Creature {
	protected $owner = null;
	protected $distanceToOwner = 0;
	public $closeTarget = null;
	public function saveNBT() {
		
	}
	public function setOwner(Player $player) {
		$this->owner = $player;
	}
	public function spawnTo(Player $player) {
		if(!$this->closed ) {
			if (!isset($this->hasSpawned[$player->getId()]) && isset($player->usedChunks[Level::chunkHash($this->chunk->getX(), $this->chunk->getZ())])) {
				$pk = new AddEntityPacket();
				$pk->eid = $this->getID();
				$pk->type = static::NETWORK_ID;
				$pk->x = $this->x;
				$pk->y = $this->y;
				$pk->z = $this->z;
				
				$pk->speedX = 0;
				$pk->speedY = 0;
				$pk->speedZ = 0;
				$pk->yaw = $this->yaw;
				$pk->pitch = $this->pitch;
				$pk->metadata = $this->dataProperties;
				if (static::NETWORK_ID == 66){
					$pk->metadata = [
							15 => [0,1],
							20 => [2,86]
					];
					$pk->y = $this->y + 0.6;
				}
				$player->dataPacket($pk);
				$this->hasSpawned[$player->getId()] = $player;
			}
		}
	}
	public function updateMovement() {
		if (
				$this->lastX !== $this->x || $this->lastY !== $this->y || $this->lastZ !== $this->z || $this->lastYaw !== $this->yaw || $this->lastPitch !== $this->pitch
		) {
			$this->lastX = $this->x;
			$this->lastY = $this->y;
			$this->lastZ = $this->z;
			$this->lastYaw = $this->yaw;
			$this->lastPitch = $this->pitch;
		}
		$this->level->addEntityMovement($this->chunk->getX(), $this->chunk->getZ(), $this->id, $this->x, $this->y, $this->z, $this->yaw, $this->pitch);
	}
	public function attack($damage, EntityDamageEvent $source) {
		
	}
	public function move($dx, $dy, $dz) {
		$this->boundingBox->offset($dx, 0, 0);
		$this->boundingBox->offset(0, 0, $dz);
		$this->boundingBox->offset(0, $dy, 0);
		$this->setComponents($this->x + $dx, $this->y + $dy, $this->z + $dz);		
		return true;
	}
	public function getSpeed() {
		return 1;
	}
	public function updateMove() {
		if(is_null($this->closeTarget)) {
			$x = $this->owner->x - $this->x;
			$z = $this->owner->z - $this->z;
		} else {
			$x = $this->closeTarget->x - $this->x;
			$z = $this->closeTarget->z - $this->z;
		}
		if ($x ** 2 + $z ** 2 < 4) {
			$this->motionX = 0;
			$this->motionZ = 0;
			$this->motionY = 0;
			if(!is_null($this->closeTarget)) {
				$this->close();
			}
			return;
		} else {
			$diff = abs($x) + abs($z);
			$this->motionX = $this->getSpeed() * 0.15 * ($x / $diff);
			$this->motionZ = $this->getSpeed() * 0.15 * ($z / $diff);
		}
		$this->yaw = -atan2($this->motionX, $this->motionZ) * 180 / M_PI;
		if(is_null($this->closeTarget)) {
			$y = $this->owner->y - $this->y;
		} else {
			$y = $this->closeTarget->y - $this->y;
		}
		$this->pitch = $y == 0 ? 0 : rad2deg(-atan2($y, sqrt($x ** 2 + $z ** 2)));
		$dx = $this->motionX;
		$dz = $this->motionZ;
		$newX = Math::floorFloat($this->x + $dx);
		$newZ = Math::floorFloat($this->z + $dz);
		$block = $this->level->getBlock(new Vector3($newX, Math::floorFloat($this->y), $newZ));
		if (!($block instanceof Air) && !($block instanceof Liquid)) {
			$block = $this->level->getBlock(new Vector3($newX, Math::floorFloat($this->y + 1), $newZ));
			if (!($block instanceof Air) && !($block instanceof Liquid)) {
				$this->motionY = 0;
				if(is_null($this->closeTarget)) {
					$this->returnToOwner();
					return;
				}
			} else {
				if (!$block->canBeFlowedInto) {
					$this->motionY = 1.1;
				} else {
					$this->motionY = 0;
				}
			}
		} else {
			$block = $this->level->getBlock(new Vector3($newX, Math::floorFloat($this->y - 1), $newZ));
			if (!($block instanceof Air) && !($block instanceof Liquid)) {
				$blockY = Math::floorFloat($this->y);
				if ($this->y - $this->gravity * 4 > $blockY) {
					$this->motionY = -$this->gravity * 4;
				} else {
					$this->motionY = ($this->y - $blockY) > 0 ? ($this->y - $blockY) : 0;
				}
			} else {
				$this->motionY -= $this->gravity * 4;
			}
		}
		$dy = $this->motionY;
		$this->move($dx, $dy, $dz);
		$this->updateMovement();
	}
	public function onUpdate($currentTick) {
		if(!($this->owner instanceof Player) || $this->owner->closed) {
			$this->fastClose();
			return false;
		}

		if($this->getHealth() == 0){
			return false;
		}
		
		if(!$this->isAlive()){
			return false;
		}
		
		if($this->closed){
			return false;
		}
		$tickDiff = $currentTick - $this->lastUpdate;
		$this->lastUpdate = $currentTick;
		if (is_null($this->closeTarget) && $this->distance($this->owner) > 40) {
			$this->returnToOwner();
		}
		$this->entityBaseTick($tickDiff);
		$this->updateMove();
		$this->checkChunks();
		return true;
	}
	public function returnToOwner() {
		$len = rand(2, 6);
		$x = (-sin(deg2rad( $this->owner->yaw))) * $len  +  $this->owner->getX();
		$z = cos(deg2rad( $this->owner->yaw)) * $len  +  $this->owner->getZ();
		$this->x = $x;
		$this->y = $this->owner->getY() + 1;
		$this->z = $z;
	}
	
	public function fastClose() {
		parent::close();
	}
	public function close(){
		if(!($this->owner instanceof Player) || $this->owner->closed) {
			$this->fastClose();
			return;
		}
		if(is_null($this->closeTarget)) {
// 			$len = rand(12, 15);
// 			$x = (-sin(deg2rad( $this->owner->yaw + 20))) * $len  +  $this->owner->getX();
// 			$z = cos(deg2rad( $this->owner->yaw + 20)) * $len  +  $this->owner->getZ();
// 			$this->closeTarget = new Vector3($x, $this->owner->getY() + 1, $z);
			$this->kill();
			$this->despawnFromAll();
			$this->setHealth(0);
		} else {
			if (isset(main::$pet[$this->owner->getName()])) {
				$this->kill();
				$this->despawnFromAll();
				$this->setHealth(0);
			}
		}
	}
	
	/**
	 * Return interval from started to current time in minutes
	 * 
	 * @param string $started
	 * @return float
	 */
	public static function getTimeInterval($started) {
		return round((strtotime(date('Y-m-d H:i:s')) - strtotime($started)) /60);	
	}
	
}
