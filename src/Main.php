<?php

declare(strict_types=1);

namespace LemoniqPvP\ProjectileEditor;

use LemoniqPvP\ProjectileEditor\item\Egg;
use LemoniqPvP\ProjectileEditor\item\ExperienceBottle;
use LemoniqPvP\ProjectileEditor\item\Snowball;
use LemoniqPvP\ProjectileEditor\listeners\ProjectileListener;
use pocketmine\item\Arrow;
use pocketmine\item\EnderPearl;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\PotionType;
use pocketmine\item\SplashPotion;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{

    public static self $instance;

    public Config $config;

    const PROJECTILE_IDS = [
        "snowball" => [ItemIds::SNOWBALL, EntityIds::SNOWBALL, "Snowball"],
        "ender_pearl" => [ItemIds::ENDER_PEARL, EntityIds::ENDER_PEARL, "Ender Pearl"],
        "arrow" => [ItemIds::ARROW, EntityIds::ARROW, "Arrow"],
        "egg" => [ItemIds::EGG, EntityIds::EGG, "Egg"],
        "xp_bottle" => [ItemIds::EXPERIENCE_BOTTLE, EntityIds::XP_BOTTLE, "Bottle O' Enchanting"],
        "splash_potion" => [ItemIds::SPLASH_POTION, EntityIds::SPLASH_POTION, "Splash Potion"]
    ];

    const THROW_FORCES = [
        "snowball" => 1.5,
        "egg" => 1.5,
        "ender_pearl" => 1.5,
        "xp_bottle" => 0.7,
        "splash_potion" => 0.5
    ];

    const NETWORK_ITEM = [
        EntityIds::SNOWBALL => ItemIds::SNOWBALL,
        EntityIds::EGG => ItemIds::EGG,
        EntityIds::ARROW => ItemIds::ARROW,
        EntityIds::ENDER_PEARL => ItemIds::ENDER_PEARL,
        EntityIds::XP_BOTTLE => ItemIds::EXPERIENCE_BOTTLE,
        EntityIds::SPLASH_POTION => ItemIds::SPLASH_POTION,
    ];

    const DAMAGING = [
        "snowball", "egg", "ender_pearl", "arrow"
    ];

    /** @var ProjectileModification[] $itemModifications */
    public array $itemModifications = [];

    public function onEnable(): void
    {
        self::$instance = $this;
        $this->config = new Config(
            $this->getDataFolder() . "config.yml",
            Config::YAML,
            (function () {
                $rv = [];
                foreach (self::PROJECTILE_IDS as $id => $data) {
                    $itemId = self::PROJECTILE_IDS[$id][0];
                    $entityId = self::PROJECTILE_IDS[$id][1];
                    $name = self::PROJECTILE_IDS[$id][2];

                    $rv[$id] = [];

                    if (isset(self::THROW_FORCES[$id])) {
                        $rv[$id]["throw_force"] = self::THROW_FORCES[$id];
                    }

                    if (in_array($id, self::DAMAGING)) {
                        $rv[$id]["hit_delay"] = 10;
                    }
                }
                return $rv;
            })()
        );
        $this->makeProjectileModifications();
        $this->registerItems();
        $this->getServer()->getPluginManager()->registerEvents(new ProjectileListener(), $this);
    }

    public function makeProjectileModifications() {
        foreach ($this->config->getAll() as $key => $config) {
            if (isset(self::PROJECTILE_IDS[$key])) {
                $this->itemModifications[self::PROJECTILE_IDS[$key][0]] = new ProjectileModification($config["throw_force"] ?? null, $config["hit_delay"] ?? null);
            }
        }
    }

    public function registerItems() {
        foreach ($this->config->getAll() as $key => $config) {
            if (isset(self::PROJECTILE_IDS[$key])) {
                ItemFactory::getInstance()->register(
                    self::createItem(
                        $key,
                        $this->itemModifications[self::PROJECTILE_IDS[$key][0]] ?? new ProjectileModification()
                    ), 
                    true
                );
            }
        }
    }

    public static function createItem(string $id, ProjectileModification $mod) {
        switch ($id) {

            case "snowball": {
                return new Snowball(new ItemIdentifier(self::PROJECTILE_IDS[$id][0], 0), self::PROJECTILE_IDS[$id][2], $mod);
            }

            case "egg": {
                return new Egg(new ItemIdentifier(self::PROJECTILE_IDS[$id][0], 0), self::PROJECTILE_IDS[$id][2], $mod);
            }

            case "ender_pearl": {
                return new EnderPearl(new ItemIdentifier(self::PROJECTILE_IDS[$id][0], 0), self::PROJECTILE_IDS[$id][2], $mod);
            }

            case "splash_potion": {
                foreach (PotionType::getAll() as $potionType) {
                    return new SplashPotion(new ItemIdentifier(self::PROJECTILE_IDS[$id][0], 0), self::PROJECTILE_IDS[$id][2], $potionType, $mod);
                }
            }

            case "xp_bottle": {
                return new ExperienceBottle(new ItemIdentifier(self::PROJECTILE_IDS[$id][0], 0), self::PROJECTILE_IDS[$id][2], $mod);
            }

            case "arrow": {
                return new Arrow(new ItemIdentifier(self::PROJECTILE_IDS[$id][0], 0), self::PROJECTILE_IDS[$id][2]);
            }

            default: {
                return new Snowball(new ItemIdentifier(self::PROJECTILE_IDS[$id][0], 0), self::PROJECTILE_IDS[$id][2], $mod);
            }

        }
    }
}
