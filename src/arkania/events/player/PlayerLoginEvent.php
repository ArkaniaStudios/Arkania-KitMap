<?php
declare(strict_types=1);

namespace arkania\events\player;

use arkania\items\CustomItemManager;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\ItemComponentPacket;

class PlayerLoginEvent implements Listener {

    public function onPlayerLogin(\pocketmine\event\player\PlayerLoginEvent $event) : void {
        $player = $event->getPlayer();

        $player->getNetworkSession()->sendDataPacket(ItemComponentPacket::create(
            CustomItemManager::getInstance()->getComponentsEntries()
        ));

    }

}