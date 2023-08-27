<?php

/*
 *
 *     _      ____    _  __     _      _   _   ___      _                 _   _   _____   _____  __        __   ___    ____    _  __
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \               | \ | | | ____| |_   _| \ \      / /  / _ \  |  _ \  | |/ /
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \     _____    |  \| | |  _|     | |    \ \ /\ / /  | | | | | |_) | | ' /
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \   |_____|   | |\  | | |___    | |     \ V  V /   | |_| | |  _ <  | . \
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            |_| \_| |_____|   |_|      \_/\_/     \___/  |_| \_\ |_|\_\
 *
 * Arkania is a Minecraft Bedrock server created in 2019,
 * we mainly use PocketMine-MP to create content for our server
 * but we use something else like WaterDog PE
 *
 * @author Arkania-Team
 * @link https://arkaniastudios.com
 *
 */

declare(strict_types=1);

namespace arkania\items;

use arkania\items\base\CustomItemBase;
use pocketmine\nbt\tag\CompoundTag;

class ItemTest extends CustomItemBase {

	public function getNbt() : CompoundTag {
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
							->setInt("max_stack_size", 1)
							->setTag(
								"minecraft:icon",
								CompoundTag::create()
									->setString("texture", "diamond")
							)
					)
			)
			->setInt("id", $this->getTypeId())
			->setString("name", CustomItemTypeNames::ITEM_TEST);
	}

	public function getMaxStackSize() : int {
		return 1;
	}

}
