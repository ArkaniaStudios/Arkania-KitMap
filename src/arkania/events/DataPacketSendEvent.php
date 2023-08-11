<?php
declare(strict_types=1);

namespace arkania\events;

use arkania\items\CustomItemManager;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\ResourcePackStackPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\types\Experiments;

class DataPacketSendEvent implements Listener {

    public function onDataPacketSend(\pocketmine\event\server\DataPacketSendEvent $event): void {
        foreach ($event->getPackets() as $packet) {
            if ($packet instanceof StartGamePacket) {
                $packet->itemTable = array_merge($packet->itemTable, CustomItemManager::getInstance()->getItemsEntries());
            } else if($packet instanceof ResourcePackStackPacket) {
                $packet->experiments = new Experiments([
                    "data_driven_items" => true
                ], true);
            }
        }
    }

}