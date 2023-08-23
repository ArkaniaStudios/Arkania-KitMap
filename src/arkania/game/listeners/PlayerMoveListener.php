<?php
declare(strict_types=1);

namespace arkania\game\listeners;

use arkania\economy\EconomyManager;
use arkania\game\MoneyZoneManager;
use arkania\Main;
use arkania\player\CustomPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\scheduler\ClosureTask;

class PlayerMoveListener implements Listener {

    /** @var (string|bool)[] */
    private array $used = [];

    public function onPlayerMove(PlayerMoveEvent $event) : void {
        $player = $event->getPlayer();

        if (!$player instanceof CustomPlayer) return;

        MoneyZoneManager::getInstance()->checkIfIsInMoneyZone($player);
        if ($player->isInMoneyZone() && !isset($this->used[$player->getName()])) {
            $this->used[$player->getName()] = true;
            $task = Main::getInstance()->getScheduler()->scheduleRepeatingTask(
                new ClosureTask(
                    function () use ($player, &$task) : void {
                        if (!$player->isOnline()) {
                            unset($this->used[$player->getName()]);
                            $task->cancel();
                        }
                        /** @phpstan-ignore-next-line */
                        if (!$player->isInMoneyZone()) {
                            unset($this->used[$player->getName()]);
                            $task->cancel();
                            return;
                        }
                        EconomyManager::getInstance()->addMoney($player->getName(), 1);
                        $player->sendPopup('§a+1$');
                    }
                ),
                20 * 5
            );
        }

    }

}