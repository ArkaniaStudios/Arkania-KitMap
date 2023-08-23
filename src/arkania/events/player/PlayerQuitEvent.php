<?php
declare(strict_types=1);

namespace arkania\events\player;

use arkania\combatlogger\CombatLoggerManager;
use arkania\player\CustomPlayer;
use pocketmine\event\Listener;

class PlayerQuitEvent implements Listener {

    public function onPlayerQuit(\pocketmine\event\player\PlayerQuitEvent $event) : void {
        $player = $event->getPlayer();

        if (!$player instanceof CustomPlayer) return;

        if (CombatLoggerManager::getInstance()->isInCombat($player->getName())){
            $player->kill();
        }

        $event->setQuitMessage('[§c-§f] ' . $player->getRankFullFormat());
    }

}