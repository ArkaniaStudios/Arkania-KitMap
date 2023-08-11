<?php
declare(strict_types=1);

namespace arkania\items;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\utils\CloningRegistryTrait;

/**
 * @method static ItemTest ITEM_TEST()
 */
class ExtraCustomItems {
    use CloningRegistryTrait;

    protected static function register(string $name, Item $item) : void{
        self::_registryRegister($name, $item);
    }

    /**
     * @return Item[]
     * @phpstan-return array<string, Item>
     */
    public static function getAll() : array{
        //phpstan doesn't support generic traits yet :(
        /** @var Item[] $result */
        $result = self::_registryGetAll();
        return $result;
    }

    protected static function setup() : void{
        self::register("item_test", new ItemTest(new ItemIdentifier(ItemTypeIds::newId()), "Item Test"));
    }


}