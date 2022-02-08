<?php

namespace LemoniqPvP\ProjectileEditor\item;

use LemoniqPvP\ProjectileEditor\ProjectileModification;
use pocketmine\item\ExperienceBottle as ItemExperienceBottle;
use pocketmine\item\ItemIdentifier;

class ExperienceBottle extends ItemExperienceBottle {

    private ProjectileModification $mod;

    public function __construct(ItemIdentifier $id, string $name, ProjectileModification $mod)
    {
        parent::__construct($id, $name);
        $this->mod = $mod;
    }

    public function getThrowForce(): float
    {
        return $this->mod->getThrowForce() ?? 1.5;
    }

}