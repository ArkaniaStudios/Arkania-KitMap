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
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use arkania\ranks\RanksManager;
use pocketmine\command\CommandSender;

class KickCommand extends BaseSubCommand {

	public function __construct() {
		parent::__construct(
			'kick'
		);
	}

	protected function registerArguments() : array {
		return [
			new StringArgument('member', false)
		];
	}

	/**
	 * @throws FactionArgumentInvalidException
	 * @throws \JsonException
	 */
	public function onRun(CommandSender $player, array $args) : void {
		if (!$player instanceof CustomPlayer) return;

		if (!$player->hasFaction()) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_no_have());
			return;
		}

		$faction = $player->getFaction();
		if (!($faction->isOwner($player->getName()) || $faction->isOfficier($player->getName()))){
			$player->sendMessage(CustomTranslationFactory::arkania_faction_no_permission());
			return;
		}

		$member = $args['member'];

		if (!$faction->isMember($member)) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_not_in_faction($member));
			return;
		}

		if ($faction->isOwner($member)) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_cant_kick_owner());
			return;
		}

		if ($faction->isOfficier($player->getName()) && $faction->isOfficier($member)) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_cant_kick_same_rank());
			return;
		}

		if ($faction->isOfficier($member)) {
			$faction->removeOfficier($member);
		}
		$faction->removeMember($member);
		$player->sendMessage(CustomTranslationFactory::arkania_faction_kick_success($member));

		$config = PlayerManager::getInstance()->getPlayerData($member);
		$config->remove('faction');
		$config->save();
		$member = PlayerManager::getInstance()->getPlayerInstance($member);
		if ($member !== null){
			RanksManager::getInstance()->updateNametag($member->getRank(), $member);
		}
		$member?->sendMessage(CustomTranslationFactory::arkania_faction_kick_by($faction->getName(), $player->getName()));
	}
}
