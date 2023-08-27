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

	public function onDataPacketSend(\pocketmine\event\server\DataPacketSendEvent $event) : void {
		foreach ($event->getPackets() as $packet) {
			if ($packet instanceof StartGamePacket) {
				$packet->itemTable = array_merge($packet->itemTable, CustomItemManager::getInstance()->getItemsEntries());
			} elseif($packet instanceof ResourcePackStackPacket) {
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
