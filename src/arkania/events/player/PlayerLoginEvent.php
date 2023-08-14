<?php
declare(strict_types=1);

namespace arkania\events\player;

use arkania\items\CustomItemManager;
use arkania\player\CustomPlayer;
use arkania\player\PlayerCreateData;
use arkania\player\PlayerCreateDataFailureException;
use arkania\player\PlayerManager;
use arkania\ranks\RanksManager;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\ItemComponentPacket;

class PlayerLoginEvent implements Listener {

    public function onPlayerLogin(\pocketmine\event\player\PlayerLoginEvent $event) : void {
        $player = $event->getPlayer();

        if (!$player instanceof CustomPlayer)
            return;

        if (!PlayerManager::getInstance()->exist($player->getName())) {
            try {
                PlayerManager::getInstance()->createPlayer(new PlayerCreateData($player->getName()));
            } catch (PlayerCreateDataFailureException $exception) {
                $player->kick($exception->getMessage());
                return;
            }
            RanksManager::getInstance()->register($player);

            $player->getNetworkSession()->sendDataPacket(ItemComponentPacket::create(CustomItemManager::getInstance()->getComponentsEntries()));

        }
    }
}