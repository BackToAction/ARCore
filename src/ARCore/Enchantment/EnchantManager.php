<?php

namespace ARCore\Enchantment;

use pocketmine\entity\Arrow;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;

class EnchantManager implements Listener {

    public function onProjectile(ProjectileHitEvent $e){
        /** @var Player $p */
        if ($e->getEntity() instanceof Arrow and ($p = $e->getEntity()->shootingEntity) instanceof Player){
            $e->getEntity()->kill();
            if ($p->getInventory()->getItemInHand()->getEnchantment(Enchantment::INFINITY) !== null){
                $p->getInventory()->addItem(Item::ARROW, 0, 1);
            }
        }
    }

    public function onDamage(EntityDamageEvent $e){
        /** @var Player $p */
        $p = $e->getEntity();
        if (!$p instanceof Player){
            return;
        }

        if ($e instanceof EntityDamageByEntityEvent){
            /** @var Player $pl */
            $pl = $e->getDamager();
            if (!$pl instanceof Player){
                return;
            }
            $it = $pl->getInventory()->getItemInHand();
            if (!$it->hasEnchantments()){
                return;
            }
            $en = $it->getEnchantments();
           /* if (isset($en[Enchantment::SHARPNESS])){
                $lvl = $en[Enchantment::SHARPNESS];
                $e->setDamage($e->getDamage()+($lvl*1.25));
            }*/
            if (isset($en[Enchantment::KNOCKBACK])){
                $lvl = $en[Enchantment::KNOCKBACK];
                $e->setKnockBack($e->getKnockBack()+($lvl*0.3));
            }
         /*   if (isset($en[Enchantment::FIRE_ASPECT])){
                $lvl = $en[Enchantment::FIRE_ASPECT];
                if (!$e->isCancelled()){
                    $p->setOnFire($lvl*4);
                }
            }*/
            if (isset($en[Enchantment::POWER])){
                $lvl = $en[Enchantment::POWER];
                $dmg = \round((($lvl+1)/4));
                $e->setDamage($e->getDamage()+$dmg);
            }
            if (isset($en[Enchantment::PUNCH])){
                $lvl = $en[Enchantment::PUNCH];
                $e->setKnockBack($e->getKnockBack()+($lvl*0.4));
            }
            if (isset($en[Enchantment::FLAME])){
                if (!$e->isCancelled()){
                    $p->setOnFire(5);
                }
            }
            if (isset($en[Enchantment::INFINITY])){
                $pl->getInventory()->addItem(Item::ARROW, 0, 1);
            }
        }

        foreach ($p->getInventory()->getArmorContents() as $item){
            $ench = $item->getEnchantments();
           /* if (isset($ench[Enchantment::PROTECTION])){
                $lvl = $ench[Enchantment::PROTECTION];
                $e->setDamage(($lvl*0.04)*$e->getDamage());
            }
            if (isset($ench[Enchantment::FIRE_PROTECTION])){
                if (\in_array($e->getCause(), [EntityDamageEvent::CAUSE_FIRE, EntityDamageEvent::CAUSE_FIRE_TICK, EntityDamageEvent::CAUSE_LAVA])){
                    $lvl = $ench[Enchantment::FIRE_PROTECTION];
                    $e->setDamage(($lvl*0.08)*$e->getDamage());
                }
            }
            if (isset($ench[Enchantment::FEATHER_FALLING])){
                if ($e->getCause() === EntityDamageEvent::CAUSE_FALL){
                    $lvl = $ench[Enchantment::FEATHER_FALLING];
                    $e->setDamage(($lvl*0.1)*$e->getDamage());
                }
            }
            if (isset($ench[Enchantment::PROJECTILE_PROTECTION])){
                if ($e->getCause() === EntityDamageEvent::CAUSE_PROJECTILE){
                    $lvl = $ench[Enchantment::PROJECTILE_PROTECTION];
                    $e->setDamage(($lvl*0.09)*$e->getDamage());
                }
            }
            if (isset($ench[Enchantment::THORNS])){
                if ($e instanceof EntityDamageByEntityEvent){
                    $lvl = $ench[Enchantment::THORNS];
                    /** @var Player $pl
                    $pl = $e->getDamager();
                    Server::getInstance()->getPluginManager()->callEvent($ev = new EntityDamageEvent($pl, EntityDamageEvent::CAUSE_CUSTOM, ($lvl*1)));
                    if ($ev->isCancelled() or $ev->getDamage() === 0){
                        return;
                    }
                    $pl->attack(($lvl*1), $ev);
                }
            }*/
        }

    }

}
