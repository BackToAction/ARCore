<?php

namespace ARCore\Particle\effects;

use pocketmine\level\particle\RedstoneParticle;

/**
 * The redstone particle effect
 */
class RedstoneParticleEffect implements ParticleEffect {

	/**
	 * Run the particle effect
	 *
	 * @param  integer $currentTick The current tick
	 * @param  Player $player       The player to fix the effect for
	 * @param  array $showTo        The players to show the particle to
	 * @return null
	 */
	public function tick($currentTick, $player, $showTo) {
		if ($player->lastUpdate < $currentTick - 5) {

			$v = 2 * M_PI / 120 * ($n % 120);
			$i = 2 * M_PI / 70 * ($n % 70);
			$x = cos($i);
			$y = cos($v);
			$z = sin($i);

			$player->getLevel()->addParticle(new RedstoneParticle($player->add($x, 1 - $y, -$z)), $showTo);
			$player->getLevel()->addParticle(new RedstoneParticle($player->add(-$x, 1 - $y, $z)), $showTo);
		} else {
			$distance = -0.5 + lcg_value();
			$yaw = $player->yaw * M_PI / 180;
			$x = $distance * cos($yaw);
			$z = $distance * sin($yaw);
			$y = lcg_value() * 0.4;
			$player->getLevel()->addParticle(new RedstoneParticle($player->add($x, $y, $z)), $showTo);
		}
	}

}
