<?php

namespace ARCore\Particle\effects;

/**
 * A model of what a particle effect should have
 */
interface ParticleEffect {

	/**
	 * The tick function
	 * 
	 * @param  [type] $currentTick [description]
	 * @param  [type] $player      [description]
	 * @param  [type] $showTo      [description]
	 * @return [type]              [description]
	 */
	public function tick($currentTick, $player, $showTo);
}
