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

namespace arkania\factions\commands\subs;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseSubCommand;
use arkania\factions\FactionArgumentInvalidException;
use arkania\factions\FactionManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\ranks\RanksManager;
use pocketmine\command\CommandSender;

class CreateCommand extends BaseSubCommand {

	public function __construct() {
		parent::__construct(
			'create'
		);
	}

	protected function registerArguments() : array {
		return [
			new StringArgument('factionName', false)
		];
	}

	/**
	 * @throws FactionArgumentInvalidException
	 */
	public function onRun(CommandSender $player, array $args) : void {
		if (!$player instanceof CustomPlayer) return;

		$name = $args['factionName'];
		if (strlen($name) < 3 || strlen($name) > 16) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_name_invalid());
			return;
		}

		if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_name_format_invalid());
			return;
		}

		if (FactionManager::getInstance()->exist($name)) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_already_exist($name));
			return;
		}

		if ($player->hasFaction()) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_already_have());
			return;
		}

		if (FactionManager::getInstance()->createFaction($name, $player)) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_created($name));
			foreach ($player->getServer()->getOnlinePlayers() as $onlinePlayer){
				if ($onlinePlayer instanceof CustomPlayer){
					$onlinePlayer->sendMessage(CustomTranslationFactory::arkania_faction_created_broadcast($player->getName(), $name), false);
				}
			}
			RanksManager::getInstance()->updateNametag($player->getRank(), $player);
			return;
		}
		$player->sendMessage(CustomTranslationFactory::arkania_faction_error());
	}
}
