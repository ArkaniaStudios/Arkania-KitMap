<?php
declare(strict_types=1);

namespace arkania\events\player;

use arkania\factions\FactionArgumentInvalidException;
use arkania\logs\PlayerChatLogs;
use arkania\player\CustomPlayer;
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
        $event->cancel();
        $format = RanksManager::getInstance()->getFormat($player->getRank());
        foreach ($event->getRecipients() as $recipient) {
            if (!$recipient instanceof CustomPlayer) continue;
            $recipient->sendMessage(str_replace(['{PLAYER_STATUS}', '{FACTION}', '{PLAYER_NAME}', '{MESSAGE}'], [$player->getFullRankUp(), $player->getFaction()?->getName() ?? '...', $player->getDisplayName(), $event->getMessage()], $format));
        }
        PlayerChatLogs::getInstance()->addChatMessage($player->getName(), Utils::removeColor($event->getMessage()));
        PlayerChatLogs::getInstance()->checkIfSendMessage($player->getName());
    }

}