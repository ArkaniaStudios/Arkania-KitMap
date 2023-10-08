<?php
declare(strict_types=1);

namespace arkania\kits;

use arkania\permissions\Permissions;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\utils\SingletonTrait;

class KitsManager {
    use SingletonTrait;

    /** @var Kits[] */
    private array $kits = [];

    public function __construct() {
        self::setInstance($this);
        self::registerKit(
            new Kits(
                'Joueur',
                Permissions::ARKANIA_KIT_JOUEUR,
                [
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_HELMET()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_CHESTPLATE()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_LEGGINGS()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_BOOTS()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_SWORD()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::GOLDEN_APPLE()->getVanillaName())->setCount(5),
                    StringToItemParser::getInstance()->parse(VanillaItems::ENDER_PEARL()->getVanillaName())->setCount(16),
                ]
            ),
            new Kits(
                'Refill',
                Permissions::ARKANIA_KIT_JOUEUR,
                [
                    StringToItemParser::getInstance()->parse(VanillaItems::GOLDEN_APPLE()->getVanillaName())->setCount(5),
                    StringToItemParser::getInstance()->parse(VanillaItems::ENDER_PEARL()->getVanillaName())->setCount(16),
                ]
            ),
            new Kits(
                'Seigneur',
                Permissions::ARKANIA_KIT_SEIGNEUR,
                [
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_HELMET()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_CHESTPLATE()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_LEGGINGS()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_BOOTS()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_SWORD()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::GOLDEN_APPLE()->getVanillaName())->setCount(10),
                    StringToItemParser::getInstance()->parse(VanillaItems::ENDER_PEARL()->getVanillaName())->setCount(16),
                ]
            ),
            new Kits(
                'Héro',
                Permissions::ARKANIA_KIT_HERO,
                [
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_HELMET()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_CHESTPLATE()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_LEGGINGS()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_BOOTS()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND_SWORD()->getVanillaName()),
                    StringToItemParser::getInstance()->parse(VanillaItems::GOLDEN_APPLE()->getVanillaName())->setCount(15),
                    StringToItemParser::getInstance()->parse(VanillaItems::ENDER_PEARL()->getVanillaName())->setCount(16),
                ]
            )
        );
    }

    private function registerKit(Kits ...$kit): void {
        foreach ($kit as $k) {
            $this->kits[$k->getName()] = $k;
        }
    }

    public function getKit(string $name): ?Kits {
        return $this->kits[$name] ?? null;
    }

    /**
     * @return Kits[]
     */
    public function getKits(): array {
        return $this->kits;
    }

}