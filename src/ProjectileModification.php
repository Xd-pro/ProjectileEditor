<?php

namespace LemoniqPvP\ProjectileEditor;

class ProjectileModification {

    private ?float $throwForce;
    private ?int $hitDelay;
    
    public function __construct(?float $throwForce = null, ?float $hitDelay = null)
    {
        $this->throwForce = $throwForce;
        $this->hitDelay = $hitDelay;
    }

    public function getThrowForce(): float {
        return $this->throwForce;
    }

    public function getHitDelay(): float {
        return $this->hitDelay;
    }

}