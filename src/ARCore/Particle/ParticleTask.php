<?php

namespace ARCore\Particle;

use pocketmine\scheduler\PluginTask;
use ARCore\Particle\effects\ParticleEffect;

/**
 * Task used to tick up the particles
 */
class ParticleTask extends PluginTask {

	/**
	 * The parent plugin
	 *
	 * @type LBParticles\Main
	 */
	private $plugin;

	/**
	 * An array mapping players to effects
	 *
	 * @type array
	 */
	private $effects = [];

	/**
	 * Constructor function for the plugin
	 *
	 * @param LBParticles\Main $plugin The plugin
	 */
	public function __construct($plugin) {
		parent::__construct($plugin);
		$this->plugin = $plugin;
	}

	/**
	 * Sets a particle effect to a player object
	 *
	 * @param Player         $player The player to give the effect to
	 * @param ParticleEffect $effect The effect to apply
	 */
	public function setPlayerParticleEffect($player, ParticleEffect $effect) {
		$this->effects[$player->getId()] = [$player, $effect];
	}

	/**
	 * Plugin that runs when the task updates
	 *
	 * @param  integer $currentTick The current tick
	 * @return null
	 */
	public function onRun($currentTick) {
		foreach ($this->effects as $id => $data) {

			$player = $data[0];
			$effect = $data[1];

			if ($player->closed) {
				unset($this->effects[$id]);
				continue;
			}

			$showTo = $player->getViewers();
			$showTo[] = $player;
			$effect->tick($currentTick, $player, $showTo);
		}
	}

	/**
	 * Removes the effect from a player
	 *
	 * @param  Player $player The player to remove the effect from
	 * @return null
	 */
	public function removeEffect($player) {
		unset($this->effects[$player->getId()]);
	}
}
