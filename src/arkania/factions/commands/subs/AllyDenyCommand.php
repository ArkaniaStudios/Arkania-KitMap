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

use arkania\api\commands\BaseSubCommand;
use arkania\factions\Faction;
use arkania\factions\FactionArgumentInvalidException;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use pocketmine\command\CommandSender;

class AllyDenyCommand extends BaseSubCommand {

	public function __construct() {
		parent::__construct(
			'allydeny'
		);
	}

	protected function registerArguments() : array {
		return [];
	}

	/**
	 * @throws FactionArgumentInvalidException
	 */
	public function onRun(CommandSender $player, array $args) : void {
		if (!$player instanceof CustomPlayer) return;

		if (!$player->hasFaction()) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_no_have());
			return;
		}

		$faction = $player->getFaction();
		if (!$faction->isOwner($player->getName())) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_not_owner());
			return;
		}
		$factionAlly = $player->getAllyRequest();
		if ($factionAlly === null){
			$player->sendMessage(CustomTranslationFactory::arkania_faction_no_ally_request());
			return;
		}

		$factionAlly = new Faction($factionAlly);
		$player->removeAllyRequest();
		$player->sendMessage(CustomTranslationFactory::arkania_faction_ally_request_denied($factionAlly->getName()));
		$owner = PlayerManager::getInstance()->getPlayerInstance($factionAlly->getOwner());
		$owner?->sendMessage(CustomTranslationFactory::arkania_faction_ally_request_denied_2($faction->getName()));

	}

}
