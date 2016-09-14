<?php

namespace ARCore\Particle;

use ARCore\Particle\effects\LavaParticleEffect;
use ARCore\Particle\effects\ParticleEffect;
use ARCore\Particle\effects\PortalParticleEffect;
use ARCore\Particle\effects\RainbowParticleEffect;
use ARCore\Particle\effects\RedstoneParticleEffect;

/**
 * The ParticleManager to manage the particle effects
 */
class ParticleManager {

	/**
	 * The lava particle effect
	 *
	 * @type LavaParticleEffect
	 */
	public static $lava;

	/**
	 * The redstone particle effect
	 *
	 * @type RedstoneParticleEffect
	 */
	public static $redstone;

	/**
	 * The portal particle effect
	 *
	 * @type PortalParticleEffect
	 */
	public static $portal;

	/**
	 * The rainbow particle effect
	 *
	 * @type RainbowParticleEffect
	 */
	public static $rainbow;

	/**
	 * The parent plugin
	 *
	 * @type LBParticles\Main
	 */
	private $plugin;

	/**
	 * The particle ticking task
	 *
	 * @type ParticleTask
	 */
	private $task;

	/**
	 * Initialize the particle effects
	 *
	 * @return null
	 */
	public static function initParticleEffects() {
		self::$lava = new LavaParticleEffect();
		self::$redstone = new RedstoneParticleEffect();
		self::$portal = new PortalParticleEffect();
		self::$rainbow = new RainbowParticleEffect();
	}

	/**
	 * Constructs the particle manager
	 *
	 * @param LBParticles\Main $plugin The parent plugin
	 */
	public function __construct($plugin) {
		self::initParticleEffects();
		$this->plugin = $plugin;
		$this->task = new ParticleTask($this->plugin);
		$this->plugin->getServer()->getScheduler()->scheduleRepeatingTask($this->task, 3);
	}

	/**
	 * Set's the players particle effect
	 *
	 * @param Player         $player The player to apply the effect to
	 * @param ParticleEffect $effect The particle effect
	 */
	public function setPlayerParticleEffect($player, ParticleEffect $effect) {
		$this->task->setPlayerParticleEffect($player, $effect);

		return $effect;
	}

	/**
	 * Remove the particle effect from the player
	 *
	 * @param  Player $player The player to remove the effect from
	 * @return null
	 */
	public function removeEffect($player) {
		$this->task->removeEffect($player);
	}

}
