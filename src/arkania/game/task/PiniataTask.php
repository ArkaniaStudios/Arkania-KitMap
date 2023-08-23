<?php
declare(strict_types=1);

namespace arkania\game\task;

use arkania\game\PiniataManager;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\npc\type\customs\Piniata;
use pocketmine\entity\Location;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class PiniataTask extends Task {
    private string $date = '';

    public function onRun(): void {
        if (date('H:i') === '20:00' && $this->date !== date('d')){
            $this->date = date('d');
            Server::getInstance()->broadcastMessage(CustomTranslationFactory::arkania_piniata_start());
            $position = PiniataManager::getInstance()->getPositions();
            $entity = new Piniata(new Location($position['x'], $position['y'], $position['z'], Main::getInstance()->getServer()->getWorldManager()->getDefaultWorld(), 0, 0));
            $entity->setNameTag('§l§cPiniata' . "\n\n" . str_repeat('§a|', 10));
            $entity->setHealth(1000);
            $entity->setNameTagAlwaysVisible();
            $entity->spawnToAll();
        }
    }
}