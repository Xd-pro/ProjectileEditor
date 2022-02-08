<?php

namespace LemoniqPvP\ProjectileEditor\item;

use LemoniqPvP\ProjectileEditor\ProjectileModification;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\PotionType;
use pocketmine\item\SplashPotion as ItemSplashPotion;

class SplashPotion extends ItemSplashPotion {

    private ProjectileModification $mod;

    public function __construct(ItemIdentifier $id, string $name, PotionType $potionType, ProjectileModification $mod)
    {
        parent::__construct($id, $name, $potionType);
        $this->mod = $mod;
    }

    public function getThrowForce(): float
    {
        return $this->mod->getThrowForce() ?? 0.7;
    }

}