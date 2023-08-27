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

namespace arkania\events\inventory;

use arkania\player\CustomPlayer;
use arkania\utils\Utils;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\Listener;

class InventoryCloseEvent implements Listener {
	public function onInventoryClose(\pocketmine\event\inventory\InventoryCloseEvent $event) : void {
		$player = $event->getPlayer();

		if (!$player instanceof CustomPlayer) {
			return;
		}

        if ($player->getInventoryType() === 'faction_chest') {
            $faction = $player->getFaction();
            foreach ($event->getInventory()->getContents() as $slot => $item) {
                $faction->addChestContent($slot, $item);
            }

        }

		if ($player->isInInventory()) {
			Utils::sendFakeBlock($player, VanillaBlocks::AIR(), 0, 3, 0);
			$player->removeInventory();
		}
	}
}
