<?php
namespace ARCore\AntiHack;

use ARCore\AntiHack\AntiHack;
use pocketmine\scheduler\Task;
use pocketmine\Server;

/**
 * Make permanent checks for cheaters
 */
class AntiHackTick extends Task {
	/**@var AntiHack*/
	private $plugin;
	/**@var string*/
	private $serverIp;
	/**@var string*/
	private $serverName;
	/**@var string*/
	private $path;


	public function __construct() {
		$this->plugin = AntiHack::getInstance();
		$this->serverIp = Server::getInstance()->getIp();
		$this->serverName = "server";
		$this->path = Server::getInstance()->getDataPath() . "logs/";
	}

	/**
	 * Kick player if his hack score is maximum,
	 * or unset hack score for honest players
	 *
	 * @param $currentTick
	 */
	public function onRun($currentTick) {
		$players = Server::getInstance()->getDefaultLevel()->getPlayers();
		foreach ($this->plugin->hackScore as $playerId => $data){
			$this->plugin->hackScore[$playerId]["integral"] += $data["score"] - 1;
			if($this->plugin->hackScore[$playerId]["integral"] < 0) {
				$this->plugin->hackScore[$playerId]["integral"] = 0;
			}
			if($data["score"] >= 3) {
				$this->plugin->hackScore[$playerId]["suspicion"]++;
			}
			$log =  str_pad(date("G:i"), 12) . str_pad($players[$playerId]->getName(), 20) . str_pad("SCORE: " . round($this->plugin->hackScore[$playerId]["score"], 2), 20) . str_pad("HINT: " . round($this->plugin->hackScore[$playerId]["integral"], 2), 20) . str_pad("SUS: " . $this->plugin->hackScore[$playerId]["suspicion"], 20) . "REASON: " . implode("/", $this->plugin->hackScore[$playerId]["reason"]) . "\n";
			$filename = $this->path . date('Y.m.d') . '_' . $this->serverName . '_hack.txt';
			if(!file_exists($filename)) {
				$title = "#Hacking Log File\n#HINT = Hacking Integration\n#SCORE = Hacking score\n#SUS = How much they are supected of hacking.\n"
						. str_pad("Time (UTC)", 12) . str_pad("Player Name", 20) . str_pad("Hacking Score", 20) . str_pad("Hacking Integration", 20) . str_pad("Suspicion Count", 20) ."Reason\n";
				@file_put_contents($filename, $title, FILE_APPEND | LOCK_EX);
			}
			if($this->plugin->hackScore[$playerId]["integral"] > 0) {
				@file_put_contents($filename, $log, FILE_APPEND | LOCK_EX);
			}

			$scoreToKick = 5;
			if($this->plugin->hackScore[$playerId]["suspicion"] >= 8){
				$scoreToKick = 3;
			} elseif($this->plugin->hackScore[$playerId]["suspicion"] >= 4 ){
				$scoreToKick = 4;
			}

			if($players[$playerId]->hackingFlag) {
				$scoreToKick--;
			}
			if(strpos($players[$playerId]->getName(), 'hack') !== false || strpos($players[$playerId]->getName(), 'hqck') !== false) {
				$scoreToKick--;
			}

			if($this->plugin->hackScore[$playerId]["integral"] > $scoreToKick){
				$log =  str_pad(date("G:i"), 12) . str_pad($players[$playerId]->getName(), 20) . str_pad("", 20) . str_pad("HINT: " . round($this->plugin->hackScore[$playerId]["integral"], 2), 20) . str_pad("SUS: " . $this->plugin->hackScore[$playerId]["suspicion"], 20) . "Player kicked \n";
				//$log = date("G:i") . " " . str_pad($players[$playerId]->getName(), 20) . " HINT: " . round($this->plugin->hackScore[$playerId]["integral"], 2) . " SUS: " . $this->plugin->hackScore[$playerId]["suspicion"] . " Player kicked \n";
				@file_put_contents($filename, $log, FILE_APPEND | LOCK_EX);
				$players[$playerId]->kick("Cheating is not permitted here. Sorry!");
			} elseif($this->plugin->hackScore[$playerId]["integral"] <= 0 && $this->plugin->hackScore[$playerId]["suspicion"] <= 0){
				unset($this->plugin->hackScore[$playerId]);
			} else{
				$this->plugin->hackScore[$playerId]["score"] = 0;
				$this->plugin->hackScore[$playerId]["reason"] = array();
			}
		}
	}
}