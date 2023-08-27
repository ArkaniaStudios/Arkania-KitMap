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
			$combatLogger = CombatLoggerManager::getInstance();
			foreach ([$damager, $entity] as $player) {
				if (!$combatLogger->isInCombat($player->getName())) {
					$player->sendMessage(CustomTranslationFactory::arkania_combatlogger_your_in_combat());
				}
				$combatLogger->addPlayer($player->getName(), 15);
			}
		}
	}
}
