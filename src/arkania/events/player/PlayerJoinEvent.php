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

use arkania\commands\player\ScoreBoardCommand;
use arkania\economy\EconomyManager;
use arkania\economy\events\PlayerCreateAccountEvent;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\ranks\RanksManager;
use arkania\server\MaintenanceManager;
use arkania\tasks\ScoreBoardTask;
use arkania\titles\TitleManager;
use pocketmine\event\Listener;

class PlayerJoinEvent implements Listener {
	public function onPlayerJoin(\pocketmine\event\player\PlayerJoinEvent $event) : void {
		$player = $event->getPlayer();

		if (!$player instanceof CustomPlayer) {
			return;
		}

		if (!$player->hasPlayedBefore()){
			$player->addTitle(TitleManager::getInstance()->getTitle('Nouveau'));
		}

		if (MaintenanceManager::getInstance()->isInMaintenance()){
			if (!$player->hasPermission(Permissions::ARKANIA_MAINTENANCE_BYPASS)){
				$player->disconnect($player->getLanguage()->translate(CustomTranslationFactory::arkania_maintenance_kick2(MaintenanceManager::getInstance()->getDate(), Main::DISCORD)));
			}
		}

		if (!isset(ScoreBoardCommand::$scoreboard[$player->getName()])) {
			ScoreBoardCommand::$scoreboard[$player->getName()] = $player->getName();
		}
		Main::getInstance()->getScheduler()->scheduleRepeatingTask(new ScoreBoardTask($player), 20);

		if (!EconomyManager::getInstance()->hasAccount($player->getName())) {
			(new PlayerCreateAccountEvent($player))->call();
		}

		RanksManager::getInstance()->updateNametag($player->getRank(), $player);
		$event->setJoinMessage('[§a+§r] ' . $player->getRankFullFormat());
	}
}
