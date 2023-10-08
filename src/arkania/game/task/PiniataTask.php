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

namespace arkania\game\task;

use arkania\game\KothManager;
use arkania\game\PiniataManager;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\npc\type\customs\Piniata;
use pocketmine\entity\Location;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class PiniataTask extends Task {
	private string $date = '';

	public function onRun() : void {
        if (date('H:i') === '18:30' && $this->date !== date('d')) {
            KothManager::getInstance()->setStatus(true);
            $this->date = date('d');
            Server::getInstance()->broadcastMessage(CustomTranslationFactory::arkania_koth_start());
        }

		if (date('H:i') === '22:00' && $this->date !== date('d')){
            PiniataManager::getInstance()->setStatus(true);
			$this->date = date('d');
			Server::getInstance()->broadcastMessage(CustomTranslationFactory::arkania_piniata_start());
			$position = PiniataManager::getInstance()->getPositions();
			$entity = new Piniata(new Location($position['x'], $position['y'], $position['z'], Main::getInstance()->getServer()->getWorldManager()->getWorldByName('spawn'), 0, 0));
			$entity->setNameTag('§l§cPiniata' . "\n\n" . str_repeat('§a|', 10));
			$entity->setHealth(1000);
			$entity->setNameTagAlwaysVisible();
			$entity->spawnToAll();
		}
	}
}
