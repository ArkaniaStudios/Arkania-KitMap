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

namespace arkania\player;

use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use JsonException;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class PlayerManager {
	use SingletonTrait;

	public function __construct() {
		if (!file_exists($this->getPlayerDataPath())) {
			mkdir($this->getPlayerDataPath());
		}
	}

	public function exist(string $name) : bool {
		return file_exists($this->getPlayerDataPath($name));
	}

	private function getPlayerDataPath(?string $name = null) : string {
		if ($name === null) {
			return Main::getInstance()->getDataFolder() . 'players/';
		}
		return Main::getInstance()->getDataFolder() . 'players/' . $name . '.json';
	}

	public function isOnline(string $name) : bool {
		return Server::getInstance()->getPlayerExact($name) !== null;
	}

	public function getPlayerInstance(string $name) : ?CustomPlayer {
		if ($this->isOnline($name)) {
			$target = Main::getInstance()->getServer()->getPlayerExact($name);
			if ($target instanceof CustomPlayer){
				return $target;
			}
			return null;
		}
		return null;
	}

	public function getPlayerData(string $name) : Config {
		return Path::config('players/' . $name, PathTypeIds::JSON());
	}

	/**
	 * @throws PlayerCreateDataFailureException
	 * @throws JsonException
	 */
	public function createPlayer(PlayerCreateData $create) : void {
		if (!$create->create()) {
			throw new PlayerCreateDataFailureException('Une erreur c\'est produite lors de la création du joueur');
		}
	}

	/**
	 * @throws JsonException
	 */
	public function removePlayerData(string $name) : void {
		$economy = Path::config('economy/economy', PathTypeIds::JSON());
		if ($economy->exists($name)){
			$economy->remove($name);
		}
		$economy->save();
		unlink($this->getPlayerDataPath($name));
		unlink(Server::getInstance()->getDataPath() . 'players/' . $name . '.dat');
	}

}
