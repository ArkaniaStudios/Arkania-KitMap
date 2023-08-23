<?php
declare(strict_types=1);

namespace arkania\combatlogger\event;

use arkania\combatlogger\CombatLoggerManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\event\Listener;

class EntityDamageByEntityEvent implements Listener {

    public function onEntityDamageByEntity(\pocketmine\event\entity\EntityDamageByEntityEvent $event) : void {
        $damager = $event->getDamager();
        $entity = $event->getEntity();
        if($damager instanceof CustomPlayer && $entity instanceof CustomPlayer) {
            if ($damager->isCreative() || $entity->isCreative()) return;
            if ($event->isCancelled()) return;
            foreach ([$damager, $entity] as $player) {
                if (!CombatLoggerManager::getInstance()->isInCombat($player->getName())) {
                    $player->sendMessage(CustomTranslationFactory::arkania_combatlogger_your_in_combat());
                }
                CombatLoggerManager::getInstance()->addPlayer($player->getName(), 15);
            }
        }
    }
}