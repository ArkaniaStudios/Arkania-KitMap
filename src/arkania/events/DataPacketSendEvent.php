<?php
declare(strict_types=1);

namespace arkania\events;

use arkania\items\CustomItemManager;
use arkania\utils\Loader;
use pocketmine\event\Listener;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AvailableActorIdentifiersPacket;
use pocketmine\network\mcpe\protocol\ResourcePackStackPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\types\Experiments;
use pocketmine\utils\AssumptionFailedError;

class DataPacketSendEvent implements Listener {

    public function onDataPacketSend(\pocketmine\event\server\DataPacketSendEvent $event): void {
        foreach ($event->getPackets() as $packet) {
            if ($packet instanceof StartGamePacket) {
                $packet->itemTable = array_merge($packet->itemTable, CustomItemManager::getInstance()->getItemsEntries());
            } else if($packet instanceof ResourcePackStackPacket) {
                $packet->experiments = new Experiments([
                    "data_driven_items" => true
                ], true);
            }elseif($packet instanceof AvailableActorIdentifiersPacket) {
                $customNamespaces = Loader::getCustomNamespaces();
                $base = $packet->identifiers->getRoot();
                $nbt = $base->getListTag("idlist");
                foreach ($customNamespaces as $index => $namespace) {
                    $components = CompoundTag::create()
                        ->setString("bid", "")
                        ->setByte("hasspawnegg", 0)
                        ->setString("id", $namespace)
                        ->setInt("rid", 200 + $index)
                        ->setByte("summonable", 1);
                    if($nbt === null) {
                        throw new AssumptionFailedError("\$nbt === null");
                    }
                    $nbt->push($components);
                }
            }
        }
    }

}