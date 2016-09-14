<?php

namespace ARCore\Particle\effects;

use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\PortalParticle;

/**
 * The effect for the portal
 */
class PortalParticleEffect implements ParticleEffect {

	/**
	 * Run the particle effect
	 *
	 * @param  integer $currentTick The current tick
	 * @param  Player $player       The player to fix the effect for
	 * @param  array $showTo        The players to show the particle to
	 * @return null
	 */
	public function tick($currentTick, $player, $showTo) {
		$player->getLevel()->addParticle(new DustParticle($player->add(-0.5 + lcg_value(), 1.5 + lcg_value() / 2, -0.5 + lcg_value()), 255, 0, 255), $showTo);
		$player->getLevel()->addParticle(new PortalParticle($player->add(-0.5 + lcg_value(), 0.5 + lcg_value(), -0.5 + lcg_value())), $showTo);
	}

}
