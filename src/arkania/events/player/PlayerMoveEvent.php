<?php
declare(strict_types=1);

namespace arkania\events\player;

use pocketmine\event\Listener;

class PlayerMoveEvent implements Listener {

    public function onPlayerMove(\pocketmine\event\player\PlayerMoveEvent $event) : void {
        $player = $event->getPlayer();
    }

}