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

namespace arkania\factions\task;

use arkania\Main;
use arkania\player\CustomPlayer;
use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use pocketmine\world\format\Chunk;
use pocketmine\world\particle\RedstoneParticle;

class SeeChunkTask extends Task {

	public function onRun() : void {
		foreach (Main::getInstance()->getServer()->getOnlinePlayers() as $player) {
			if (!$player instanceof CustomPlayer) continue;
			if (!$player->isChunkView()) continue;
			$position = $player->getPosition();
			$chunkX = $position->getFloorX() >> Chunk::COORD_BIT_SIZE;
			$chunkZ = $position->getFloorZ() >> Chunk::COORD_BIT_SIZE;

			$minX = (float) $chunkX * 16;
			$minZ = (float) $chunkZ * 16;
			$maxX = $minX + 16;
			$maxZ = $minZ + 16;

			for ($x = $minX; $x <= $maxX; $x += 0.5) {
				for ($z = $minZ; $z <= $maxZ; $z += 0.5) {
					if ($x === $minX || $x === $maxX || $z === $minZ || $z === $maxZ) {
						$player->getWorld()->addParticle(new Vector3($x, $position->y + 1.5, $z), new RedstoneParticle(), [$player]);
					}
				}
			}
		}
	}
}
