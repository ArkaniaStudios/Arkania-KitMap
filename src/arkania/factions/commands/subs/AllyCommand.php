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
use arkania\factions\Faction;
use arkania\factions\FactionArgumentInvalidException;
use arkania\factions\FactionManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use pocketmine\command\CommandSender;

class AllyCommand extends BaseSubCommand {

	public function __construct() {
		parent::__construct('ally');
	}

	protected function registerArguments() : array {
		return [new StringArgument('factionName')];
	}

	/**
	 * @throws FactionArgumentInvalidException
	 */
	public function onRun(CommandSender $player, array $args) : void {
		if (!$player instanceof CustomPlayer)
			return;

		if (!$player->hasFaction()) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_no_have());
			return;
		}

		$faction = $player->getFaction();
		if (!$faction->isOwner($player->getName())) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_not_owner());
			return;
		}
		$factionAlly = $args['factionName'];
		if (!FactionManager::getInstance()->exist($factionAlly)) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_not_exists($factionAlly));
			return;
		}

		if (count($faction->getAllyManager()->getAllies()) > 2) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_ally_max());
			return;
		}

		$factionAlly = new Faction($factionAlly);
		if ($faction->getAllyManager()->isAlly($factionAlly)) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_ally_already($factionAlly->getName()));
			return;
		}

		if (count($factionAlly->getAllyManager()->getAllies()) > 2){
			$player->sendMessage(CustomTranslationFactory::arkania_faction_ally_max());
			return;
		}

		$factionOwner = PlayerManager::getInstance()->getPlayerInstance($factionAlly->getOwner());
		if ($factionOwner === null) {
			$player->sendMessage(CustomTranslationFactory::arkania_player_not_found($factionAlly->getOwner()));
			return;
		}

		$factionOwner->sendMessage(CustomTranslationFactory::arkania_faction_ally_request($faction->getName()));
		$player->sendMessage(CustomTranslationFactory::arkania_faction_ally_request_sent($factionAlly->getName()));
		$factionOwner->addAllyRequest($faction->getName());
	}

}
