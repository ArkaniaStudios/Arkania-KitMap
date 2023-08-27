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

use arkania\api\commands\arguments\TargetArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\teleportation\TeleportationManager;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class TpaHereCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			'tpahere',
			CustomTranslationFactory::arkania_teleportation_tpa_description()
		);
	}

	protected function registerArguments() : array {
		return [
			new TargetArgument('target')
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		if (!$player instanceof CustomPlayer) return;

		if (count($parameters) !== 1){
			throw new InvalidCommandSyntaxException();
		}

		$target = $player->getServer()->getPlayerExact($parameters['target']);

		if (!$target instanceof CustomPlayer){
			$player->sendMessage(CustomTranslationFactory::arkania_player_not_found($parameters['target']));
			return;
		}
		TeleportationManager::getInstance()->sendTeleportationToPlayer($player, $target);
	}
}
