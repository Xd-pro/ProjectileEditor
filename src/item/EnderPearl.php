<?php

namespace LemoniqPvP\ProjectileEditor\item;

use LemoniqPvP\ProjectileEditor\ProjectileModification;
use pocketmine\item\EnderPearl as ItemEnderPearl;
use pocketmine\item\ItemIdentifier;

class EnderPearl extends ItemEnderPearl {

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