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

namespace arkania\game;

use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use arkania\player\CustomPlayer;
use pocketmine\utils\SingletonTrait;

class MoneyZoneManager {
	use SingletonTrait;

	/** @var (string|mixed)[] */
	private array $positions = [];

	public function __construct() {
		if (!file_exists(Main::getInstance()->getDataFolder() . "moneyzone")) {
			mkdir(Main::getInstance()->getDataFolder() . "moneyzone");
		}
	}

	public function createMoneyZone() : void {
		$path = Path::config('moneyzone/infos', PathTypeIds::YAML());
		$path->set('minX', $this->positions['minX']);
		$path->set('minZ', $this->positions['minZ']);
		$path->set('maxX', $this->positions['maxX']);
		$path->set('maxZ', $this->positions['maxZ']);
		$path->save();
	}

	/**
	 * @param (string|mixed)[] $pos1
	 * @param (string|mixed)[] $pos2
	 */
	public function setPositions(array $pos1, array $pos2) : void {
		$this->positions['minX'] = min($pos1['x'], $pos2['x']);
		$this->positions['minZ'] = min($pos1['z'], $pos2['z']);
		$this->positions['maxX'] = max($pos1['x'], $pos2['x']);
		$this->positions['maxZ'] = max($pos1['z'], $pos2['z']);
	}

	public function checkIfIsInMoneyZone(CustomPlayer $player) : void {
		$path = Path::config('moneyzone/infos', PathTypeIds::YAML());
		$minX = $path->get('minX');
		$minZ = $path->get('minZ');
		$maxX = $path->get('maxX');
		$maxZ = $path->get('maxZ');
		$position = $player->getPosition();
		if ($position->getX() >= $minX && $position->getX() <= $maxX && $position->getZ() >= $minZ && $position->getZ() <= $maxZ) {
			$player->setInMoneyZone(true);
		} else {
			$player->setInMoneyZone(false);
		}
	}

}
