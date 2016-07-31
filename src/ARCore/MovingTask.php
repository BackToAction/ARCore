<?php

namespace ARCore;


use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\Task;

class MovingTask extends Task{

    public $plugin;

    const WALKING_SPEED = 4.3;
    const SPRINTING_SPEED = 6.1;
    const SNEAKING_SPEED = 1.3;
    const FLYING_SPEED = 10.8;

    public function __construct(ARCore $plugin){
        $this->plugin = $plugin;
    }

    public function onRun($currentTick){
        foreach($this->plugin->cheatData as $name => $data){
            $p = $this->plugin->players[$name];
            if($p->isOnGround()){
                $data["lastGround"] = $p->getPosition();
                $data["airTime"] = 0;
                $data["jump"] = false;
            }else{
                $pos = $p->getPosition();
                $data["airtime"]++;
                if($pos->y >= $data["lastPos"]->y && $data["airtime"] >= 3){
                    $p->teleport($data["lastPos"]);
                    return;
                }
            }
            $this->checkSpeed($data, $p);
        }
    }

    private function getMaxDistance(Player $p) {
        // Speed potions?
        $effects = $p->getEffects();

        $amplifier = 0;

        // Check for speed potions.
        if(!empty($effects)) {
            foreach($effects as $effect) {
                if($effect->getId() == Effect::SPEED) {

                    // In-case there is more than one speed effect on a player, get the max.
                    if(($a = $effect->getAmplifier()) > $amplifier) {
                        $amplifier = $a;
                    }
                }
            }
        }
        $speed = self::WALKING_SPEED;

        if($p->isSprinting()) $speed = self::SPRINTING_SPEED;
        elseif($p->isSneaking()) $speed = self::SNEAKING_SPEED;

        $distance = $speed + ($amplifier != 0) ? ($speed / (0.2 * $amplifier)) : 0;

        return $distance;
    }

    public function checkSpeed($data, Player $p){
        $prev = $data["lastPos"];
        $current = $p->getPosition();
        if(!($prev instanceof Position)) {
            return;
        }
        if($prev->getLevel() != $current->getLevel()) {
            return;
        }
        $maxDistance = $this->getMaxDistance($p);
        // Ignore Y values (in case of jump boosts etc)
        $actualDistance = sqrt(abs(($prev->getX() - $current->getX()) * ($prev->getZ() - $current->getZ())));
        $diff = $maxDistance - $actualDistance;
        if($diff > 0) {
            // I CALL HAX!
            $p->teleport($prev);
        }
        // Store current variables for the next tick
    }
}
