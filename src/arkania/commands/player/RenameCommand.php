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

use arkania\api\commands\arguments\TextArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\utils\Utils;
use pocketmine\command\CommandSender;

class RenameCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			'rename',
			CustomTranslationFactory::arkania_rename_description(),
			'/rename <name>',
			permission: Permissions::ARKANIA_RENAME
		);
	}

	protected function registerArguments() : array {
		return [
			new TextArgument('name')
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		if (!$player instanceof CustomPlayer) return;

		$name = $parameters['name'];
		if (strlen($name) < 3 || strlen($name) > 32) {
			$player->sendMessage(CustomTranslationFactory::arkania_rename_error_length());
			return;
		}

		if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
			$player->sendMessage(CustomTranslationFactory::arkania_rename_error_format());
			return;
		}
		$player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCustomName(Utils::removeColor($name)));
		$player->sendMessage(CustomTranslationFactory::arkania_rename_success($name));
	}
}
