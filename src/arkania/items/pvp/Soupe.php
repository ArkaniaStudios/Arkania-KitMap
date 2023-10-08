<?php
declare(strict_types=1);

namespace arkania\items\pvp;

use arkania\items\base\CustomItemBase;
use arkania\items\CustomItemTypeNames;
use pocketmine\nbt\tag\CompoundTag;

class Soupe extends CustomItemBase {

    public function getNbt(): CompoundTag {
        return CompoundTag::create()
            ->setTag(
                "components",
                CompoundTag::create()
                    ->setTag(
                        "item_properties",
                        CompoundTag::create()
                            ->setByte("allow_off_hand", 0)
                            ->setByte("can_destroy_in_creative", 1)
                            ->setInt("creative_category", 4)
                            ->setString("creative_group", "")
                            ->setInt("max_stack_size", 64)
                            ->setTag(
                                "minecraft:icon",
                                CompoundTag::create()
                                    ->setString("texture", "soupe")
                            )
                    )
            )
            ->setInt("id", $this->getTypeId())
            ->setString("name", CustomItemTypeNames::SOUPE);
    }

    public function getMaxStackSize(): int {
        return 64;
    }

}