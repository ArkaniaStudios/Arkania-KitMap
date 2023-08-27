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

namespace arkania\commands\staff;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\PlayerManager;
use pocketmine\command\CommandSender;

class DeleteUserCommand extends BaseCommand{
	public function __construct() {
		parent::__construct(
			'deleteuser',
			CustomTranslationFactory::arkania_deleteuser_description(),
			'/deleteuser <player>',
			[],
			['deleteu'],
			Permissions::ARKANIA_DELETEUSER
		);
	}

	protected function registerArguments() : array {
		return [
			new StringArgument('target')
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		$target = $parameters['target'];
		if (!PlayerManager::getInstance()->exist($target)) {
			$player->sendMessage(CustomTranslationFactory::arkania_player_no_exist($target));
			return;
		}
		PlayerManager::getInstance()->removePlayerData($target);
	}

}
