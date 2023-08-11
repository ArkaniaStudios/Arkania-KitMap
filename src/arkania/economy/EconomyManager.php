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

namespace arkania\economy;

use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use JsonException;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class EconomyManager {
    use SingletonTrait;
	private Config $config;

	public function __construct() {
		if (!file_exists(Main::getInstance()->getDataFolder() . 'economy/')) {
			mkdir(Main::getInstance()->getDataFolder() . 'economy/');
		}
		$this->config = Path::config('economy/economy', PathTypeIds::JSON());
	}

	public function getMoney(string $playerName) : int {
		if ($this->config->exists($playerName)) {
			return $this->config->get($playerName);
		} else {
			return -55;
		}
	}

	/**
	 * @throws JsonException
	 */
	public function addMoney(string $playerName, int $amount) : void {
		if ($this->config->exists($playerName)) {
			$this->config->set($playerName, $this->config->get($playerName) + $amount);
		} else {
			$this->config->set($playerName, $amount);
		}
		$this->config->save();
		$this->config->reload();
	}

	/**
	 * @throws JsonException
	 */
	public function delMoney(string $playerName, int $amount) : void {
		if ($this->config->exists($playerName)) {
			$this->config->set($playerName, $this->config->get($playerName) - $amount);
		} else {
			$this->config->set($playerName, $amount);
		}
		$this->config->save();
		$this->config->reload();
	}

	/**
	 * @throws JsonException
	 */
	public function setMoney(string $playerName, int $amount) : void {
		$this->config->set($playerName, $amount);
		$this->config->save();
		$this->config->reload();
	}

	/**
	 * @throws JsonException
	 */
	public function createAccount(string $playerName) : void {
		$this->config->set($playerName, 500);
		$this->config->save();
		$this->config->reload();
	}

	public function hasAccount(string $playerName) : bool {
		$this->config->reload();

		return $this->config->exists($playerName);
	}
}
