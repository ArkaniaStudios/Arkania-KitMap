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
use arkania\ranks\RanksManager;
use arkania\sanctions\mute\MuteManager;
use arkania\utils\Utils;
use pocketmine\event\Listener;

class PlayerChatEvent implements Listener {

    /**
     * @param $temps
     * @return string
     */
    final public function tempsFormat($temps): string {
        $timeRestant = (int)$temps - time();
        $jours = floor(abs($timeRestant / 86400));
        $timeRestant = $timeRestant - ($jours * 86400);
        $heures = floor(abs($timeRestant / 3600));
        $timeRestant = $timeRestant - ($heures * 3600);
        $minutes = floor(abs($timeRestant / 60));
        $secondes = ceil(abs($timeRestant - $minutes * 60));

        if($jours > 0)
            $format = $jours . ' jour(s) et ' .  $heures . ' heure(s)';
        else if($heures > 0)
            $format = $heures . ' heure(s) et ' . $minutes . ' minute(s)';
        else if($minutes > 0)
            $format = $minutes . ' minute(s) et ' . $secondes . ' seconde(s)';
        else
            $format = $secondes . 'seconde(s)';
        return $format;
    }

	/**
	 * @throws FactionArgumentInvalidException
	 */
	public function onPlayerChat(\pocketmine\event\player\PlayerChatEvent $event) : void {
		$player = $event->getPlayer();

		if (!$player instanceof CustomPlayer) return;
		$message = $event->getMessage();

        $mute = MuteManager::getInstance()->getMute($player->getName());
        if ($mute !== null){
            $sanction = $mute;
            $staff = $sanction->getSanctioner();
            $temps = $sanction->getExpirationDate();
            $raison = $sanction->getReason();

            if ($temps - time() <= 0)
                MuteManager::getInstance()->removeMute($player->getName());
            else{
                $player->sendMessage(Utils::getPrefix() . "§cVous êtes actuellement mute." . PHP_EOL . PHP_EOL . '§cStaff: ' . $staff . PHP_EOL . '§cTemps: §e' . $this->tempsFormat($temps) . PHP_EOL . '§cRaison: §e' . $raison . PHP_EOL);
                $event->cancel();
            }
        }

		if (str_contains($message, '@everyone') || str_contains($message, '@here')) {
            $message = str_replace('@', '@ ', $message);
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
