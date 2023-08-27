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
/**
 *     _      ____    _  __     _      _   _   ___      _                   _      ____    ___
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \                 / \    |  _ \  |_ _|
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____      / _ \   | |_) |  | |
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____|    / ___ \  |  __/   | |
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\           /_/   \_\ |_|     |___|
 *
 * @author: Julien
 * @link: https://github.com/ArkaniaStudios
 */

namespace arkania\npc\type\customs;

use arkania\npc\base\SimpleEntity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class FloatingText extends SimpleEntity {

	public float $gravity = 0.0;

	protected function getInitialSizeInfo() : EntitySizeInfo {
		return new EntitySizeInfo(0.5, 0.7);
	}
	public static function getNetworkTypeId() : string {
		return EntityIds::FALLING_BLOCK;
	}

	public function getName() : string {
		return 'floatingText';
	}

	public function spawnTo(Player $player) : void {
		$this->setNpc();
		$this->setNameTagAlwaysVisible();
		$this->setNameTag(str_replace('{LINE}', "\n", $this->getCustomName()));
		parent::spawnTo($player);
	}
}
