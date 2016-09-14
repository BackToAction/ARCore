<?php

namespace ARCore\AntiHack;

use pocketmine\plugin\Plugin;
use pocketmine\Server;

use ARCore\AntiHack\AntiHackEventListener;
use ARCore\AntiHack\AntiHackTick;

/**
 * Base class for antihack add-on,
 * calls event listener and task
 */
class AntiHack {
	/**@var array*/
	public $hackScore = array();	
	/** @var AntiHack */
	static private $instance;
	//protected
	private function __construct() {}	
	private function __clone() {}	
	public function __destruct() {}
	
	/**
	 * 
	 * implementation of singleton pattern
	 * 
	 * @return AntiHack
	 */
	static public function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Enable EventKistener and Task
	 * 
	 * @param Plugin $plugin
	 */
	static public function enable(Plugin $plugin) {
		self::getInstance();
		Server::getInstance()->getPluginManager()->registerEvents(
			new AntiHackEventListener(), $plugin
		);
		
		Server::getInstance()->getScheduler()->scheduleRepeatingTask(
			new AntiHackTick(), 40
		);
		Server::getInstance()->getScheduler()->scheduleRepeatingTask(
			new AntiHackSuspicionTick(), 20 * 60
		);
	}		
}
