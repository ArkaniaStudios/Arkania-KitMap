<?php
declare(strict_types=1);

namespace arkania\items\base;

use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;

abstract class  CustomItemBase extends Item {

    abstract public function getNbt() : CompoundTag;

}