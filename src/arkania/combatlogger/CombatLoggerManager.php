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

namespace arkania\combatlogger;

use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\player\CustomPlayer;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\SingletonTrait;

class CombatLoggerManager {
	use SingletonTrait;

	public function __construct() {
		$this->task();
	}

	/** @var (string|int)[] */
	private array $players = [];

	public function addPlayer(string $name, int $time) : void {
		$this->players[$name] = $time;
	}

	public function removePlayer(string $name) : void {
		unset($this->players[$name]);
	}

	public function getPlayers() : array {
		return $this->players;
	}

	public function getPlayer(string $name) : ?int {
		return $this->players[$name] ?? null;
	}

	public function isInCombat(string $name) : bool {
		return isset($this->players[$name]);
	}

	private function task() : void {
		Main::getInstance()->getScheduler()->scheduleRepeatingTask(new ClosureTask(
			function () : void {
				foreach ($this->players as $playerName => $time) {
					$time--;
					$this->players[$playerName]--;
					if ($time <= 0)
					{
						unset($this->players[$playerName]);
						$player = Main::getInstance()->getServer()->getPlayerExact($playerName);
						if ($player instanceof CustomPlayer) {
							$player->sendMessage(CustomTranslationFactory::arkania_combatlogger_you_are_not_in_combat());
						}
					}
				}
			}
		), 20);
	}

}
