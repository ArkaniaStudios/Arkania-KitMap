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

use arkania\api\commands\arguments\TargetArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use pocketmine\command\CommandSender;

class SelfTpCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			's',
			CustomTranslationFactory::arkania_teleport_description(),
			'/selftp <player>',
			aliases: ['selftp'],
			permission: Permissions::ARKANIA_TELEPORT
		);
	}

	protected function registerArguments() : array {
		return [
			new TargetArgument('target')
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		if (!$player instanceof CustomPlayer) return;
		$target = PlayerManager::getInstance()->getPlayerInstance($parameters['target']);
		$target->teleport($player->getPosition());
		$player->sendMessage(CustomTranslationFactory::arkania_teleport_success_self($target->getName()));
	}

}
