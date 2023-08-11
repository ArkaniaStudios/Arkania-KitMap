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

use arkania\api\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\permissions\Permissions;
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

	public function execute(CommandSender $player, string $commandLabel, array $args) : void {
		if (!$player instanceof CustomPlayer) {
			return;
		}

		if (count($args) === 0) {
			throw new InvalidCommandSyntaxException();
		}

		if ($args[0] === 'on') {
			if ($player->isInLogs()) {
				$player->sendMessage(CustomTranslationFactory::arkania_logs_already('activé'));

				return;
			}
			$player->setLogs(true);
			$player->sendMessage(CustomTranslationFactory::arkania_logs_on());
		} elseif ($args[0] === 'off') {
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
