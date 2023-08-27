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

namespace arkania\events\player;

use arkania\combatlogger\CombatLoggerManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\rankup\RankUpManager;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;

class PlayerDeathEvent implements Listener {

	/**
	 * @throws \JsonException
	 */
	public function onPlayerDeath(\pocketmine\event\player\PlayerDeathEvent $event) : void {
		$sender = $event->getPlayer();
		$cause = $sender->getLastDamageCause();
		if ($cause instanceof EntityDamageByEntityEvent) {
			$damager = $cause->getDamager();
			if ($damager instanceof CustomPlayer) {
				$damager->addKill();
				/** @var CustomPlayer $player */
				foreach ([$damager, $sender] as $player) {
					CombatLoggerManager::getInstance()->removePlayer($player->getName());
					$player->sendMessage(CustomTranslationFactory::arkania_combatlogger_you_are_not_in_combat());
				}
				//TODO: Implementer le système de message custom
				$rankUpInfo = RankUpManager::getInstance()->getConfig()->getRanks();
				foreach ($rankUpInfo as $rankUpData) {
					$rank = $rankUpData->getName();
					$nextStep = $rankUpData->getNextStep();
					$playerKill = $damager->getKills();
					$color = $rankUpData->getColor();
					foreach ($nextStep as $count => $step) {
						if ($playerKill === $step) {
							RankUpManager::getInstance()->setPlayerRankUp($damager->getName(), $rank, $color, $count + 1);
							$damager->sendMessage(CustomTranslationFactory::arkania_rankup_you_have_ranked_up($color . $rank . $count + 1));
						}
					}
				}
			}
		}
	}
}
