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

namespace arkania\events\player;

use arkania\factions\Faction;
use arkania\factions\FactionArgumentInvalidException;
use arkania\language\CustomTranslationFactory;
use arkania\logs\PlayerChatLogs;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use arkania\ranks\RanksManager;
use arkania\utils\Utils;
use pocketmine\event\Listener;

class PlayerChatEvent implements Listener {

	/**
	 * @throws FactionArgumentInvalidException
	 */
	public function onPlayerChat(\pocketmine\event\player\PlayerChatEvent $event) : void {
		$player = $event->getPlayer();

		if (!$player instanceof CustomPlayer) return;
		$message = $event->getMessage();
		if (mb_substr($message, 0, 1) === '@') {
			$mention = PlayerManager::getInstance()->getPlayerInstance(mb_substr($message, 1));
			if ($mention === null) {
				$message = str_replace('@', '@ ', $message);
			}
		}
		if (mb_substr($message, 0, 1) === '!') {
			if (!$player->hasFaction())
				return;
			$player->getFaction()->broadCastFactionMessage(Faction::MESSAGE_TYPE_FACTION, CustomTranslationFactory::arkania_faction_broadcast_message($player->getName(), mb_substr($message, 1)));
			PlayerChatLogs::getInstance()->addChatMessage($player->getName(), mb_substr($message, 0, 1) . ' *(Faction-Chat)*');
			$event->cancel();
		} elseif (mb_substr($message, 0, 1) === '?') {
			if (!$player->hasFaction())
				return;
			PlayerChatLogs::getInstance()->addChatMessage($player->getName(), mb_substr($message, 0, 1) . ' *(Ally-Chat)*');
			$player->getFaction()->broadCastFactionMessage(Faction::MESSAGE_TYPE_ALLY, CustomTranslationFactory::arkania_faction_broadcast_message($player->getName(), mb_substr($message, 1)));
			$player->getFaction()->broadCastFactionMessage(Faction::MESSAGE_TYPE_FACTION, CustomTranslationFactory::arkania_faction_broadcast_message($player->getName(), mb_substr($message, 1)));
			$event->cancel();
		}else{
			$event->cancel();
			$format = RanksManager::getInstance()->getFormat($player->getRank());
			foreach ($event->getRecipients() as $recipient) {
				if (!$recipient instanceof CustomPlayer) continue;
				$factionRank = $player->getFactionRank();
				if ($player->getFaction() === null) {
					$faction = '...';
				} else {
					$faction = $player->getFaction()->getName();
				}
				$recipient->sendMessage(str_replace(['{PLAYER_STATUS}', '{FACTION_RANK}', '{FACTION}', '{PLAYER_NAME}', '{MESSAGE}'], [$player->getFullRankUp(), $factionRank, $faction, $player->getDisplayName(), $message], $format));
			}
			PlayerChatLogs::getInstance()->addChatMessage($player->getName(), Utils::removeColor($message));
			PlayerChatLogs::getInstance()->checkIfSendMessage($player->getName());
		}
	}
}
