<?php
declare(strict_types=1);

namespace arkania\events\commands;

use arkania\combatlogger\CombatLoggerManager;
use arkania\language\CustomTranslationFactory;
use pocketmine\event\Listener;
use function _PHPStan_4d77e98e1\RingCentral\Psr7\str;

class CommandEvent implements Listener {

    public function onCommand(\pocketmine\event\server\CommandEvent $event) : void {
        $player = $event->getSender();
        $command = $event->getCommand();
        $server = $player->getServer();

        $commande = explode(" ", $command);
        $commande[0] = strtolower($commande[0]);
        $command = implode(" ", $commande);
        $event->setCommand($command);

        if (CombatLoggerManager::getInstance()->isInCombat($player->getName()) && in_array(explode(' ', $command), ['tpa', 'tpaccept', 'tpahere', 'tp', 'home', 'spawn', 'hub', 'heal'])) {
            $event->cancel();
            $player->sendMessage(CustomTranslationFactory::arkania_combatlogger_cant_use_command());
            return;
        }

        if (str_contains($command, '@a')) {
            $event->cancel();
            foreach ($server->getOnlinePlayers() as $onlinePlayer) {
                $server->dispatchCommand($player, str_replace('@a', $onlinePlayer->getName(), $command));
            }
        }elseif(str_contains($command, '@s')){
            $event->cancel();
            $server->dispatchCommand($player, str_replace('@s', $player->getName(), $command));
        }elseif(str_contains($command, '@r')) {
            $event->cancel();
            $server->dispatchCommand($player, str_replace(
                '@r',
                $server->getOnlinePlayers()[
                    array_rand($server->getOnlinePlayers())
                ]->getName(),
                $command
                )
            );
        }elseif(str_contains($command, '@e') || str_contains($command, '@p')) {
            $event->cancel();
            $player->sendMessage('§cTu fous quoi mon reuf');
        }
    }
}