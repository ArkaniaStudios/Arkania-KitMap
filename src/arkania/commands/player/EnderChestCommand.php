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

namespace arkania\commands\player;

use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\utils\Utils;
use pocketmine\block\Barrel;
use pocketmine\block\Chest;
use pocketmine\block\Furnace;
use pocketmine\block\inventory\EnderChestInventory;
use pocketmine\block\ShulkerBox;
use pocketmine\block\tile\EnderChest;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;

class EnderChestCommand extends BaseCommand {
	public function __construct() {
		parent::__construct(
			'enderchest',
			CustomTranslationFactory::arkania_enderchest_description(),
			'/enderchest',
            [],
			['ec'],
			Permissions::ARKANIA_ENDERCHEST
		);
	}

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) {
            return;
        }

        $position = $player->getPosition();
        $position->y += 3;
        $block = $player->getWorld()->getBlock($position);
        if ($block instanceof Chest || $block instanceof Furnace || $block instanceof Barrel || $block instanceof ShulkerBox) {
            $player->sendMessage(CustomTranslationFactory::arkania_enderchest_can_not());

            return;
        }

        Utils::sendFakeBlock($player, VanillaBlocks::ENDER_CHEST(), 0, 3, 0, 'EnderChest - ' . $player->getName(), EnderChest::class);
        $player->setInventory('enderchest');
        $player->setCurrentWindow(new EnderChestInventory($position, $player->getEnderInventory()));
    }
}
