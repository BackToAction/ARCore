<?php

namespace ARCore\ChatFilter;

use ARCore\ChatFilter\chat\ChatClasser;
use pocketmine\utils\TextFormat;

/**
 * Class to check for allowed message
 * (prevent passwords in chat, prevent dating, short and repeating messages)
 */
class ChatFilter {
	/**@var ChatClasser*/
	protected $profanityChecker;
	/**@var array*/
	protected $recentMessages = array();
	/**@var bool*/
	protected $enableMessageFrequency;
	/**@var array*/
	protected $recentChat = array();

	public function __construct($enableMsgFrequency = true) {
		$this->profanityChecker = new ChatClasser();
		$this->enableMessageFrequency = $enableMsgFrequency;
	}

	/**
	 * Clears the recent chat filter (for spam protection)
	 *
	 * @return null
	 */
	public function clearRecentChat() {
 		$this->recentChat = array();
 	}

	/**
	 * Check for valid message
	 *
	 * @param LbPlayer $player
	 * @param string $message
	 * @param boolean $needCheck
	 * @return boolean
	 */
	public function check($player, $message, $needCheck = true) {
		// Check the message and log the result.
		$checkResult = $this->profanityChecker->check($message);

		$errorMessage = $this->getErrorMessage($message, $player);
		if (!empty($errorMessage)) {
			$player->sendMessage($errorMessage);
			return false;
		}
		if($needCheck){
			if ($this->enableMessageFrequency) {
				$this->recentChat[$player->getID()] = true;
			}
			$this->recentMessages[$player->getID()] = $message;
		}
		return true;
	}

	/**
	 * Get message with suitable error
	 *
	 * @param string $message
	 * @param Player $player
	 * @return string
	 */
	private function getErrorMessage($message, $player) {
		$errorMsg = '';

		if (strlen($message) === 0) {
			$errorMsg = TextFormat::RED . ' That message is too short.';
		} elseif (isset($this->recentChat[$player->getID()])) {
 			/* player already posted message in last 3 seconds */
 			$errorMsg = TextFormat::RED . ' You are messaging too fast.';
 		} elseif (isset($this->recentMessages[$player->getID()]) &&
				$this->recentMessages[$player->getID()] === $message) {
			/* player's message repeated his previous message */
			$errorMsg = TextFormat::RED . ' You repeated that message.';
		} elseif ($this->profanityChecker->getIsProfane()) {
			$errorMsg = TextFormat::RED . ' That\'s an inappropriate message.';
		} elseif ($this->profanityChecker->getIsDating()) {
			$errorMsg = TextFormat::RED . ' No dating.';
		} elseif ($this->profanityChecker->getIsAdvertising()) {
			$errorMsg = TextFormat::RED . ' No advertising';
		}

		return $errorMsg;
	}

}
