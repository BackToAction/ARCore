<?php

namespace ARCore\Particle\effects;

use pocketmine\level\particle\DustParticle;

/**
 * The rainbow particle effect
 */
class RainbowParticleEffect implements ParticleEffect {

	/**
	 * Convert an HSV value to RGB
	 *
	 * @param  integer $h The h value
	 * @param  integer $s The s value
	 * @param  integer $v The v value
	 * @param  integer $r The r value
	 * @param  integer $g The g value
	 * @param  integer $b The b value
	 * @return null
	 */
	static function hsv2rgb($h, $s, $v, &$r, &$g, &$b) {
		$h = (($h % 360) / 359) * 6;
		$s = ($s % 101) / 100;
		$i = floor($h);
		$f = $h - $i;

		$v = ($v % 101) / 100 * 255;
		$m = $v * (1 - $s) * 255;
		$n = $v * (1 - $s * $f) * 255;
		$k = $v * (1 - $s * (1 - $f)) * 255;

		$r = $g = $b = 0;
		if ($i == 0) {
			$r = $v;
			$g = $k;
			$b = $m;
		} else if ($i == 1) {
			$r = $n;
			$g = $v;
			$b = $m;
		} else if ($i == 2) {
			$r = $m;
			$g = $v;
			$b = $k;
		} else if ($i == 3) {
			$r = $m;
			$g = $n;
			$b = $v;
		} else if ($i == 4) {
			$r = $k;
			$g = $m;
			$b = $v;
		} else if ($i == 5 || $i == 6) {
			$r = $v;
			$g = $m;
			$b = $n;
		}
	}

	/**
	 * Run the particle effect
	 *
	 * @param  integer $currentTick The current tick
	 * @param  Player $player       The player to fix the effect for
	 * @param  array $showTo        The players to show the particle to
	 * @return null
	 */
	public function tick($currentTick, $player, $showTo) {
		$n = mt_rand(0, 1);
		$this->hsv2rgb($n * 2, 100, 100, $r, $g, $b);

		if ($player->lastUpdate < $currentTick - 5) {

			$v = 2 * M_PI / 120 * ($n % 120);
			$i = 2 * M_PI / 60 * ($n % 60);
			$x = cos($i);
			$y = cos($v) * 0.5;
			$z = sin($i);

			$player->getLevel()->addParticle(new DustParticle($player->add($x, 2 - $y, -$z), $r, $g, $b), $showTo);
			$player->getLevel()->addParticle(new DustParticle($player->add(-$x, 2 - $y, $z), $r, $g, $b), $showTo);
		} else {

			for ($i = 0; $i < 2; $i++) {
				$distance = -0.5 + lcg_value();
				$yaw = $player->yaw * M_PI / 180 + (-0.5 + lcg_value()) * 90;
				$x = $distance * cos($yaw);
				$z = $distance * sin($yaw);
				$y = lcg_value() * 0.4 + 0.5;
				$player->getLevel()->addParticle(new DustParticle($player->add($x, $y, $z), $r, $g, $b), $showTo);
			}
		}
	}

}
