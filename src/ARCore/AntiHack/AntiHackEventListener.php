<?php

namespace ARCore\AntiHack;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\Player;
use pocketmine\event\Listener;
use ARCore\AntiHack\AntiHack;

/**
 * Antihack listener search for non-allowed actions like:
 * flying, fast movings, fill bucket with lava
 */
class AntiHackEventListener implements Listener {
	const PLAYER_MAX_SPEED = 8;
	/**@var AntiHack*/
	private $plugin;
	/**@var array*/
	private $flyPlayers = array();
	/**@var array*/
	private $movePlayers = array();

	public function __construct() {
		$this->plugin = AntiHack::getInstance();
	}

	/**
	 * @param EntityMotionEvent $event
	 */
	public function onEntityMotion(EntityMotionEvent $event){
		$player = $event->getEntity();
		if($player instanceof Player){
			if(isset($this->movePlayers[$player->getId()])){
				$this->movePlayers[$player->getId()]["freeze"] = 2;
			}

		}
	}

	/**
	 * Look for flying and extraspeed players, increment their hack score
	 *
	 * @param PlayerMoveEvent $event
	 */
	public function onPLayerMove(PlayerMoveEvent $event) {
		//http://minecraft.gamepedia.com/Transportation
		$player = $event->getPlayer();
		$dY = (int)(round($event->getTo()->getY() - $event->getFrom()->getY(), 3) * 1000);
		if($dY >= 0){
			$maxY = $player->getLevel()->getHighestBlockAt(floor($event->getTo()->getX()), floor($event->getTo()->getZ()));
			if($event->getTo()->getY() - 5 > $maxY) {
				$score = ($event->getTo()->getY() - $maxY) / 5;
				if(isset($this->plugin->hackScore[$player->getId()])){
					$this->plugin->hackScore[$player->getId()]["score"] += $score;
					if(!isset($this->plugin->hackScore[$player->getId()]["reason"]["Fly"])){
						$this->plugin->hackScore[$player->getId()]["reason"]["Fly"] = "Fly";
					}
				} else{
					$this->plugin->hackScore[$player->getId()] = array();
					$this->plugin->hackScore[$player->getId()]["score"] = $score;
					$this->plugin->hackScore[$player->getId()]["integral"] = 0;
					$this->plugin->hackScore[$player->getId()]["reason"] = array("Fly" => "Fly");
					$this->plugin->hackScore[$player->getId()]["suspicion"] = 0;
				}
			}
		}

		//fly vertical speed

		if($dY > 0 && $dY % 375 == 0) {
			if(isset($this->flyPlayers[$player->getId()])){
				$this->flyPlayers[$player->getId()]++;
			} else{
				$this->flyPlayers[$player->getId()] = 1;
			}
		}else{
			$this->flyPlayers[$player->getId()] = 0;
		}

		if($this->flyPlayers[$player->getId()] >= 3){
			$flyPoint = $this->flyPlayers[$player->getId()];
			$this->flyPlayers[$player->getId()] = 0;
			if(isset($this->plugin->hackScore[$player->getId()])){
				$this->plugin->hackScore[$player->getId()]["score"] += $flyPoint;
				if(!isset($this->plugin->hackScore[$player->getId()]["reason"]["Vertical speed"])){
					$this->plugin->hackScore[$player->getId()]["reason"]["Vertical speed"] = "Vertical speed";
				}
			} else{
				$this->plugin->hackScore[$player->getId()] = array();
				$this->plugin->hackScore[$player->getId()]["score"] = $flyPoint;
				$this->plugin->hackScore[$player->getId()]["integral"] = 0;
				$this->plugin->hackScore[$player->getId()]["reason"] = array("Vertical speed" => "Vertical speed");
				$this->plugin->hackScore[$player->getId()]["suspicion"] = 0;
			}
		}
		if(!isset($this->movePlayers[$player->getId()])){
			$this->movePlayers[$player->getId()] = array();
			$this->movePlayers[$player->getId()]["time"] = time();
			$this->movePlayers[$player->getId()]["distance"] = 0;
		}
		if($this->movePlayers[$player->getId()]["time"] != time()){
			if(!isset($this->movePlayers[$player->getId()]["freeze"]) || $this->movePlayers[$player->getId()]["freeze"] < 1){
				if($this->movePlayers[$player->getId()]["distance"] > self::PLAYER_MAX_SPEED * 1.1){
					if(isset($this->plugin->hackScore[$player->getId()])){
						$this->plugin->hackScore[$player->getId()]["score"] += ($this->movePlayers[$player->getId()]["distance"] - 4) / 4;
						if(!isset($this->plugin->hackScore[$player->getId()]["reason"]["Speed"])){
							$this->plugin->hackScore[$player->getId()]["reason"]["Speed"] = "Speed";
						}
					} else{
						$this->plugin->hackScore[$player->getId()] = array();
						$this->plugin->hackScore[$player->getId()]["score"] =($this->movePlayers[$player->getId()]["distance"] - 4) / 4;
						$this->plugin->hackScore[$player->getId()]["integral"] = 0;
						$this->plugin->hackScore[$player->getId()]["reason"] = array("Speed" => "Speed");
						$this->plugin->hackScore[$player->getId()]["suspicion"] = 0;
					}
				}
			} else{
				$this->movePlayers[$player->getId()]["freeze"]--;
			}
			$this->movePlayers[$player->getId()]["time"] = time();
			$this->movePlayers[$player->getId()]["distance"] = 0;
		}

		$oldPos= $event->getFrom();
		$newPos = $event->getTo();
		$this->movePlayers[$player->getId()]["distance"] += sqrt(($newPos->getX() - $oldPos->getX()) ** 2 + ($newPos->getZ() - $oldPos->getZ()) ** 2);
	}

	/**
	 * remove player from class data fields
	 *
	 * @param PlayerQuitEvent $event
	 */
	public function onPlayerQuit(PlayerQuitEvent $event) {
		$player = $event->getPlayer();
		unset($this->movePlayers[$player->getId()]);
		unset($this->flyPlayers[$player->getId()]);
		unset($this->plugin->hackScore[$player->getId()]);
	}

	/**
	 * Cancel cause of lava pour men
	 *
	 * @param PlayerBucketFillEvent $event
	 */
	public function onPlayerBucketFill(PlayerBucketFillEvent $event) {
		$event->setCancelled(true);
	}
}
