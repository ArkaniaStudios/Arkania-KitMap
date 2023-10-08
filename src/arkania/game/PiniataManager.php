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

use arkania\game\task\PiniataTask;
use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use JsonException;
use pocketmine\utils\SingletonTrait;

class PiniataManager {
	use SingletonTrait;

	/** @var (string|mixed)[] */
	private array $positions = [];

    public bool $status = false;

	public function __construct() {
		if (!file_exists(Main::getInstance()->getDataFolder() . "piniata")) {
			mkdir(Main::getInstance()->getDataFolder() . "piniata");
		}
		Main::getInstance()->getScheduler()->scheduleRepeatingTask(new PiniataTask(), 20);
	}

	/**
	 * @throws JsonException
	 */
	public function createSpawnLama() : void {
		$path = Path::config('piniata/infos', PathTypeIds::YAML());
		$path->set('x', $this->positions['x']);
		$path->set('y', $this->positions['y']);
		$path->set('z', $this->positions['z']);
		$path->save();
	}

	/**
	 * @param (string|mixed)[] $pos
	 */
	public function setPositions(array $pos) : void {
		$this->positions['x'] = $pos['x'];
		$this->positions['y'] = $pos['y'];
		$this->positions['z'] = $pos['z'];
	}

	public function getPositions() : array {
		$path = Path::config('piniata/infos', PathTypeIds::YAML());
		return $path->getAll();
	}

    public function getEventStatus() : bool {
        return $this->status;
    }

    public function setStatus(bool $status) : void {
        $this->status = $status;
    }

}
