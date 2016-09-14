<?php

namespace ARCore\Particle\effects;

use pocketmine\level\particle\LavaDripParticle;
use pocketmine\level\particle\LavaParticle;

/**
 * The lava particle effect
 */
class LavaParticleEffect implements ParticleEffect {

	/**
	 * Run the particle effect
	 *
	 * @param  integer $currentTick The current tick
	 * @param  Player $player       The player to fix the effect for
	 * @param  array $showTo        The players to show the particle to
	 * @return null
	 */
	public function tick($currentTick, $player, $showTo) {
		$player->getLevel()->addParticle(new LavaParticle($player->add(0, 1 + lcg_value(), 0)), $showTo);

		$distance = -0.5 + lcg_value();
		$yaw = $player->yaw * M_PI / 180;
		$x = $distance * cos($yaw);
		$z = $distance * sin($yaw);
		$player->getLevel()->addParticle(new LavaDripParticle($player->add($x, 0.2, $z)), $showTo);
	}

}
