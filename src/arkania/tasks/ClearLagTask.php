<?php
declare(strict_types=1);

namespace arkania\tasks;

use arkania\language\CustomTranslationFactory;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class ClearLagTask extends Task {

    public function __construct(
        private readonly int $time = 300
    ) {}

    public function onRun(): void {

        $time = $this->time;

        if ($time % 10 === 0) {
            Server::getInstance()->broadcastMessage(CustomTranslationFactory::arkania_clearlag_time((string)$time));
        }
    }

}