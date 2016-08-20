<?php
namespace ARCore\Level;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\level\Position;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\permission\ServerOperator;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\inventory\PlayerInventory;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\event\server\RemoteServerCommandEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\block\BlockBreakEvent;
use ARCore\ARCore;
class LevelSystem extends PluginBase implements Listener {
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if (!file_exists($this->getDataFolder())) {
            mkdir($this->getDataFolder(), 0744, true);
            $this->exps = new Config($this->getDataFolder() . "exps.json", Config::JSON, array());
        }
        $this->exps = new Config($this->getDataFolder() . "exps.json", Config::JSON, array());
    }
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        return false;
    }
    public function onJoin(PlayerJoinEvent $event) {
        $name = $event->getPlayer()->getName();
        if (!$this->exps->exists($name)) {
            $this->exps->set($name, 0);
            $this->exps->save();
        }
    }
    public function onPlayerDeath(PlayerDeathEvent $event) {
        $ev = $event->getEntity()->getLastDamageCause();
        if ($ev instanceof EntityDamageByEntityEvent) {
            $killer = $ev->getDamager();
            if ($killer instanceof Player) {
                $exp = $this->exps->get($killer->getName());
                $exp = intval($exp) + 8; //?o?±?l
				$killer->sendMessage("§b【Level】 Recieve 8 points of experience!");
				$killer->sendMessage("§b【Level】 Current experiences: ".$exp." points");
				$killer->sendPopUp("§b【Level】 Recieve 8 points of experience!");
				$killer->sendPopUp("§b【Level】 Exp: ".$exp." points");
                $this->exps->set($killer->getName(), $exp);
                $this->exps->save();
            }
        }
    }
}
