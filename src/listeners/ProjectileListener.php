<?php

namespace LemoniqPvP\ProjectileEditor\listeners;

use LemoniqPvP\ProjectileEditor\Main;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemIds;

class ProjectileListener implements Listener {

    public function onDamage(EntityDamageEvent $event) {
        //Main::$instance->getLogger()->info("damage detected");
        if ($event->getCause() == EntityDamageEvent::CAUSE_PROJECTILE && $event instanceof EntityDamageByChildEntityEvent) {
            //Main::$instance->getLogger()->info("projectile detected");
            $child = $event->getChild();
            if (!$child instanceof Projectile) return;
            if (isset(Main::$instance->itemModifications[Main::NETWORK_ITEM[$child->getNetworkTypeId()]])) {
                //Main::$instance->getLogger()->info("modded projectile detected");
                $mod = Main::$instance->itemModifications[Main::NETWORK_ITEM[$child->getNetworkTypeId()]];
                $event->setAttackCooldown($mod->getHitDelay());
            }
        }
    }

}