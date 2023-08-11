<?php
declare(strict_types=1);

namespace arkania\items;

use arkania\items\base\CustomItemBase;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemComponentPacketEntry;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\format\io\GlobalItemDataHandlers;

class CustomItemManager {
    use SingletonTrait;

    /**
     * @var ItemTypeEntry[]
     */
    private array $componentsEntries = [];

    /**
     * @var ItemTypeEntry[]
     */
    private array $itemsEntries = [];

    /**
     * @param string[] $stringToItemParserNames
     */
    public function registerCustomItem(string $id, CustomItemBase $item, array $stringToItemParserNames) : void{
        GlobalItemDataHandlers::getDeserializer()->map($id, fn() => clone $item);
        GlobalItemDataHandlers::getSerializer()->map($item, fn() => new SavedItemData($id));

        foreach($stringToItemParserNames as $name){
            StringToItemParser::getInstance()->register($name, fn() => clone $item);
        }

        $this->registerCustomItemMapping($id, $item->getTypeId());
        $this->registerCustomItemPacketsCache($id, $item);

    }

    private function registerCustomItemMapping(string $id, int $itemTypeId) : void {
        $dictionary = TypeConverter::getInstance()->getItemTypeDictionary();
        $reflection = new \ReflectionClass($dictionary);
        $properties = [
            ["intToStringIdMap", [$itemTypeId => $id]],
            ["stringToIntMap", [$id => $itemTypeId]]
        ];

        foreach ($properties as $data) {
            $property = $reflection->getProperty($data[0]);
            $property->setValue($dictionary, $property->getValue($dictionary) + $data[1]);
        }
    }

    private function registerCustomItemPacketsCache(string $id, CustomItemBase $item) : void {
        $this->componentsEntries[] = new ItemComponentPacketEntry($id, new CacheableNbt($item->getNbt()));
        $this->itemsEntries[] = new ItemTypeEntry($id, $item->getTypeId(), true);
    }

    /**
     * @return ItemTypeEntry[]
     */
    public function getItemsEntries() : array {
        return $this->itemsEntries;
    }

    /**
     * @return ItemComponentPacketEntry[]
     */
    public function getComponentsEntries() : array {
        return $this->componentsEntries;
    }

}