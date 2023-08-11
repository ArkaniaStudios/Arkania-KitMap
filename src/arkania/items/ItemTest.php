<?php
declare(strict_types=1);

namespace arkania\items;

use arkania\items\base\CustomItemBase;
use pocketmine\nbt\tag\CompoundTag;

class ItemTest extends CustomItemBase {

    public function getNbt(): CompoundTag {
        return CompoundTag::create()
            ->setTag("components", CompoundTag::create()
            ->setTag("item_properties", CompoundTag::create()
                ->setByte("allow_off_hand", 0)
                ->setByte("can_destroy_in_creative", 1)
                ->setInt("creative_category", 4)
                ->setString("creative_group", "")
                ->setInt("max_stack_size", 1)
                ->setTag("minecraft:icon", CompoundTag::create()
                ->setString("texture", "diamond")
                )
            )
            )
            ->setInt("id", $this->getTypeId())
            ->setString("name", CustomItemTypeNames::ITEM_TEST);
    }

    public function getMaxStackSize(): int {
        return 1;
    }

}