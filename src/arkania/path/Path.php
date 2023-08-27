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

namespace arkania\path;

use arkania\Main;
use pocketmine\utils\Config;

class Path {
	public static function config(string $path, PathTypeIds $ids) : Config {
		$datafolder = Main::getInstance()->getDataFolder();
		if ($ids->name() === 'json') {
			return new Config($datafolder . $path . '.json', Config::JSON);
		}
		if ($ids->name() === 'yaml') {
			return new Config($datafolder . $path . '.yml', Config::YAML);
		}
		if ($ids->name() === 'properties') {
			return new Config($datafolder . $path . '.properties', Config::PROPERTIES);
		}
		if ($ids->name() === 'ini') {
			return new Config($datafolder . $path . '.ini', Config::PROPERTIES);
		}
		if ($ids->name() === 'lang') {
			return new Config($datafolder . $path . '.lang', Config::PROPERTIES);
		}
		if($ids->name() === 'txt') {
			return new Config($datafolder . $path . '.txt', Config::ENUM);
		}
		return new Config($datafolder . $path . '.yml', Config::YAML);
	}
}
