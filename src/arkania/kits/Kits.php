<?php
declare(strict_types=1);

namespace arkania\kits;

use arkania\player\CustomPlayer;
use pocketmine\item\Armor;
use pocketmine\item\Item;

class Kits {

    /** @var Item[] */
    private array $items = [];

    private string $name;
    private string $permission = '';

    /**
     * @param string $name
     * @param string $permission
     * @param Item[] $items
     */
    public function __construct(
        string $name,
        string $permission,
        array $items
    ) {
        $this->name = $name;
        $this->permission = $permission;
        $this->items = $items;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPermission(): string {
        return $this->permission;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array {
        return $this->items;
    }

    public function send(CustomPlayer $player) : void {
        $inventory = $player->getInventory();
        $armorInventory = $player->getArmorInventory();
        foreach ($this->getItems() as $item) {
            if ($item instanceof Armor) {
                $armorInventory->setItem($item->getArmorSlot(), $item);
            } else {
                $inventory->addItem($item);
            }
        }
    }
}