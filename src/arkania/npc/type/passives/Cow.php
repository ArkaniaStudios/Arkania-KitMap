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
 *     _      ____    _  __     _      _   _   ___      _             __     __  ____
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \            \ \   / / |___ \
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____   \ \ / /    __) |
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____|   \ V /    / __/
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            \_/    |_____|
 *
 * @author: Julien
 * @link: https://github.com/ArkaniaStudios
 */

namespace arkania\npc\type\passives;

use arkania\npc\base\SimpleEntity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Cow extends SimpleEntity {

	public int $knockbackTick = 0;
	public int $speed = 1;
	private bool $movesByJumping = false;

	protected function getInitialSizeInfo() : EntitySizeInfo {
		return new EntitySizeInfo(1.3, 0.9);
	}

	public static function getNetworkTypeId() : string {
		return EntityIds::COW;
	}

	public function getName() : string {
		return 'cow';
	}

	/*public function onUpdate(int $currentTick): bool
	{
		$this->setNpc(false);
		if ($this->knockbackTick > 0) {
			$this->knockbackTick--;
		}
		if ($this->isAlive()) {
			if (!$this->onGround && $this->gravityEnabled) {
				if ($this->motion->y > -$this->gravity * 4) {
					$this->motion->y = -$this->gravity * 4;
				} else {
					$this->motion->y -= $this->gravity;
				}
			}
			if ($this->knockbackTick <= 0) {
				$x = $this->location->x;
				$y = $this->location->y;
				$z = $this->location->z;
				if ($x ** 2 + $y ** 2 + $z ** 2 < 0.7) {
					$this->motion->x = 0;
					$this->motion->z = 0;
				} else {
					$diff = abs($x) + abs($z);
					$this->motion->x = $this->speed * 0.15 * ($x / $diff);
					if (!$this->gravityEnabled) {
						$this->motion->y = $this->speed * 0.15 * ($y / $diff);
					}
					$this->motion->z = $this->speed * 0.15 * ($z / $diff);
					$this->location->yaw = rad2deg(atan2(-$x, $z));
					$this->location->pitch = rad2deg(atan(-$y));
					$this->move($this->motion->x, $this->motion->y, $this->motion->z);
					if ($this->isCollidedHorizontally) {
						$this->motion->y = ($this->gravityEnabled ? 0 : mt_rand(0, 1)) === 0 ? $this->jumpVelocity : -$this->jumpVelocity;
					} elseif ($this->onGround && $this->movesByJumping) {
						$this->motion->y = $this->jumpVelocity;
					}
				}
			} else {
				$this->move($this->motion->x, $this->motion->y, $this->motion->z);
			}
			$this->updateMovement();
		}
		return parent::onUpdate($currentTick);
	}*/
}
