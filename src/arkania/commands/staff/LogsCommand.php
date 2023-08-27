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
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class LogsCommand extends BaseCommand {
	public function __construct() {
		parent::__construct(
			'logs',
			CustomTranslationFactory::arkania_logs_description(),
			CustomTranslationFactory::arkania_logs_usage(),
			permission: Permissions::ARKANIA_LOGS
		);
	}

	protected function registerArguments() : array {
		return [
			new StringArgument('status')
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		if (!$player instanceof CustomPlayer) {
			return;
		}

		if ($parameters['status'] === 'on') {
			if ($player->isInLogs()) {
				$player->sendMessage(CustomTranslationFactory::arkania_logs_already('activé'));

				return;
			}
			$player->setLogs(true);
			$player->sendMessage(CustomTranslationFactory::arkania_logs_on());
		} elseif ($parameters['status'] === 'off') {
			if (!$player->isInLogs()) {
				$player->sendMessage(CustomTranslationFactory::arkania_logs_already('désactivé'));

				return;
			}
			$player->removeLogs();
			$player->sendMessage(CustomTranslationFactory::arkania_logs_off());
		} else {
			throw new InvalidCommandSyntaxException();
		}
	}
}
