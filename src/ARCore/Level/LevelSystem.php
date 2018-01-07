<?php
namespace ARCore\Level;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;


class Main extends PluginBase implements Listener {


    public function onEnable() {// this function is no need
        @mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder()."lp/"); // add this to the ARCore.php in onEnable
        $this->getLogger()->info("
          §7§l=[ §r§a================================================================================== §7§l]=

          §a                                █    ████  █   █  █████  █       
                                            █    █      █ █   █      █       
                                            █    ███    █ █   ███    █       §f§lUP
                                            ███  ████    █    █████  ███      

          §7§l=[ §r§a================================================================================== §7§l]=

                                 §eThanks Ziken and AlbanWeill for this amazing plugin
                  "); // this log doesn't needed
        $this->getServer()->getPluginManager()->registerEvents($this,$this); // check if ARCore.php have this if not go add it, but i think there is this function.
    }
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){ // need to seperate this into new file
		$config = new Config($this->main->getDataFolder()."lp/".strtolower($sender->getName()).".yml", Config::YAML);
		$p = $event->getPlayer();
        $n = $p->getName();
		if($cmd->getName() == "level"){
        $sender->sendMessage('§e=== §a Elite RPG - Thông tin về bạn '.$n.'§e===');
        $sender->sendMessage('§bLevel:§7 '.$config->get('level'));
        $sender->sendMessage('§bĐKN:§7 '.$config->get('xp').'§9/'. $config->get('level')*50);
        $sender->sendMessage('§cHP: §e'. $p->getHealth .'§9/§c'. $p->getMaxHealth);
        $sender->sendMessage('§e================================================');
		}
		if($cmd->getName() == "addexp"){
			if($sender->isOP()){
			if(is_numeric($args[0])){
				$sender->sendMessage("§e",$args[0]," ĐKN§a đã được thêm vào level của bạn");
		        $config->set('xp', $config->get('xp')+$args[0]);
		        $config->save();
			}
			else{
				$sender->sendMessage("§cĐKN phải là số!");
				return true;
			}
		}
		}
		if($cmd->getName() == "buyexp"){
			if(is_numeric($args[0])){
				if($this->eco->myMoney($s->getName()) >= $args[0] * 100){
					EconomyAPI::getInstance()->reduceMoney($sender, $args[0] * 100);
		            $sender->sendMessage("§aBạn đã mua §e",$args[0]," ĐKN§a thành công");
		            $config->set('xp', $config->get('xp')+$args[0]);
		            $config->save();
		            return true;
				}
				else{
		            $sender->sendMessage("§cBạn không đủ§f Bạc§c để mua §b$args[0] §eĐKN");
				}
			}
			else{
				$sender->sendMessage("§cĐKN phải là số!");
			}
		}
	}

    public function onJoin(PlayerJoinEvent $event){
        $config = new Config($this->getDataFolder()."lp/".strtolower($event->getPlayer()->getName()).".yml", Config::YAML);
        $config->save();
        if($config->get('xp') > 0){
            $event->getPlayer()->sendMessage("§e[Elite] Chào mừng §a".$event->getPlayer()->getName(). " trở lại server");
			Server::GetInstance()->broadcastMessage("§e[§b". $config->get('level')."§e]§a".$event->getPlayer()->getName()."§e đã vào server");
        } else {
            $config->set('level', 1);
            $config->save();
        }
    }
	public function onChat(PlayerChatEvent $event){
		$config = new Config($this->getDataFolder()."lp/".strtolower($event->getPlayer()->getName()).".yml", Config::YAML);
        $config->save();
		$p = $event->getPlayer();
		$n = $p->getName();
		$p->setDisplayName('§7[§e'. $config->get('level').'§7]§e ' .$n);
	}
    public function onMove(PlayerMoveEvent $event){
        $config = new Config($this->getDataFolder()."lp/".strtolower($event->getPlayer()->getName()).".yml", Config::YAML);
        $p = $event->getPlayer();
        $n = $p->getName();
       if($config->get('level') <= 100){
           if($config->get('xp') >= $config->get('level')*50 ){
               $config->set('xp',0);
               $config->set('level',$config->get('level') + 1);
			   $p->setMaxHealth($config->get('level') + 20);
			   $p->setHealth($config->get('level') + 20);
               Server::GetInstance()->broadcastMessage('§7§l=[ §r§aThông báo §7§l]=§r §e'.$n.'§b đã lên cấp §e'.$config->get('level'));
               $config->save();
           }
       }
    }

    public function onBlockBreak(BlockBreakEvent $event){
        $config = new Config($this->getDataFolder()."lp/".strtolower($event->getPlayer()->getName()).".yml", Config::YAML);
        $b = $event->getBlock()->getId();
        if($b == 56 or $b == 14 or $b == 15 or $b == 16 or $b == 73 or $b == 21){
       if($config->get('level') <= 150){
		   $chang = mt_rand(1,5);
		   switch ($chang){
			   case 1:
               $config->set('xp',$config->get('xp')+1);
               $event->getPlayer()->sendMessage('§b§l+1§e ĐKN');
               $config->save();
			   break;
			   case 2:
			   $config->set('xp',$config->get('xp')+3);
			   $event->getPlayer()->sendMessage('§b§l+3§e ĐKN');
			   $config->save();
			   break;
			   case 3:
			   $config->set('xp',$config->get('xp')+6);
			   $event->getPlayer()->sendMessage('§b§l+6§e ĐKN');
			   $config->save();
			   break;
			   case 4:
			   $config->set('xp',$config->get('xp')+2);
			   $event->getPlayer()->sendMessage('§b§l+2§e ĐKN');
			   $config->save();
			   break;
			   case 5:
			   $config->set('xp',$config->get('xp')+4);
			   $event->getPlayer()->sendMessage('§b§l+4§e ĐKN');
			   $config->save();
			   break;
		   }
        }
    }
	}
	public function onPlayerDeath(PlayerDeathEvent $event) {
		$config = new Config($this->getDataFolder()."lp/".strtolower($event->getPlayer()->getName()).".yml", Config::YAML);
        $ev = $event->getEntity()->getLastDamageCause();
        if ($ev instanceof EntityDamageByEntityEvent) {
            $killer = $ev->getDamager();
            if ($killer instanceof Player){
                if($config->get('level') <= 150){
					$config->set('xp', $config->get('xp')+10);
					$event->getPlayer()->sendMessage('§b§l+10§e ĐKN§a hạ gục');
					$config->save();
				}
            }
        }
    }
}
