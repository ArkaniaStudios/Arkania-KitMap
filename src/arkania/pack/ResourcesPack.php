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

namespace arkania\pack;

use arkania\Main;
use InvalidArgumentException;
use JsonException;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\utils\Config;
use ReflectionClass;
use ReflectionException;
use ZipArchive;

class ResourcesPack {
	private Main $core;

	/**
	 * @throws ReflectionException
	 * @throws JsonException
	 */
	public function __construct(Main $core, string $packName) {
		if (\file_exists($core->getDataFolder() . 'pack/' . $packName . '.zip')) {
			self::deleteDir($core->getDataFolder() . 'pack');
		}
		$this->core = $core;
		$this->loadPack($packName);
	}

	/**
	 * @throws JsonException|ReflectionException
	 */
	private function loadPack(string $packName) : void {
		$this->transferPackToData($this->core->getServer()->getPluginPath() . $this->core->getName() . '/resources', 'pack/' . $packName);
		if (self::checkAllFile($this->core->getServer()->getPluginPath() . $this->core->getName(), '/resources/pack/' . $packName, $this->core->getDataFolder(), 'pack/' . $packName)) {
			return;
		}
		@\mkdir($this->core->getDataFolder() . 'pack/' . $packName . '/');
		$manifest = new Config($this->core->getServer()->getPluginPath() . $this->core->getName() . '/resources/pack/' . $packName . '/manifest.json', Config::JSON);
		/** @var (string|mixed)[] $array */
		$array = $manifest->getAll();
		$array['header']['version'][2]++;
		$array['modules'][0]['version'][2]++;
		$manifest->setAll($array);
		$manifest->save();
		$this->core->saveResource('pack/' . $packName . '/manifest.json', true);
		$zip = new ZipArchive();
		if ($zip->open($this->core->getDataFolder() . 'pack/' . $packName . '.zip', ZipArchive::CREATE) === true) {
			self::addToZip($this->core->getDataFolder() . 'pack/' . $packName, $zip);
			$zip->close();
		}
		self::deleteDir($this->core->getDataFolder() . 'pack/' . $packName);
		$pack = new ZippedResourcePack($this->core->getDataFolder() . 'pack/' . $packName . '.zip');
		$manager = $this->core->getServer()->getResourcePackManager();
		$reflection = new ReflectionClass($manager);
		$property = $reflection->getProperty('resourcePacks');
		$currentRessourcePacks = $property->getValue($manager);
		$currentRessourcePacks[] = $pack;
		$property->setValue($manager, $currentRessourcePacks);
		$property = $reflection->getProperty('uuidList');
		$currentUUIDPacks = $property->getValue($manager);
		$currentUUIDPacks[\strtolower($pack->getPackId())] = $pack;
		$property->setValue($manager, $currentUUIDPacks);
		$property = $reflection->getProperty("serverForceResources");
		$property->setValue($manager, true);
	}

	private function transferPackToData(string $src, string $addPath = '') : void {
		$dir = \opendir($src . '/' . $addPath);
		if ($dir === false) {
			return;
		}
		while ($file = \readdir($dir)) {
			if (\is_file($src . '/' . $addPath . '/' . $file)) {
				$this->core->saveResource($addPath . '/' . $file, true);
			} else {
				if ($file != '.' && $file != '..') {
					$this->transferPackToData($src, $addPath . '/' . $file);
				}
			}
		}
	}

	private static function addToZip(string $src, ZipArchive $zip, string $file = '') : void {
		$dir = \opendir($src);
		if ($dir === false) {
			return;
		}
		while ($fichier = \readdir($dir)) {
			if (\is_file($src . '/' . $fichier)) {
				$zip->addFile($src . '/' . $fichier, $file . '/' . $fichier);
			} else {
				if ($fichier != '.' && $fichier != '..') {
					self::addToZip($src . '/' . $fichier, $zip, $file . '/' . $fichier);
				}
			}
		}
	}

	private static function deleteDir(string $dirPath) : void {
		if (!\is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (!\str_ends_with($dirPath, '/')) {
			$dirPath .= '/';
		}
		$files = \glob($dirPath . '*', \GLOB_MARK);
		if ($files === false) {
			return;
		}
		foreach ($files as $file) {
			if (\is_dir($file)) {
				self::deleteDir($file);
			} else {
				\unlink($file);
			}
		}
		\rmdir($dirPath);
	}

	private static function checkAllFile(string $basePath, string $path, string $secondBasePath, string $secondPath) : bool {
		$same = true;
		$scan = \scandir($basePath . $path);
		if ($scan === false) {
			return false;
		}
		foreach ($scan as $value) {
			if ($value === "." || $value === "..") {
				continue;
			}
			if (\is_dir($basePath . $path . "/" . $value)) {
				$same = self::checkAllFile($basePath, $path . "/" . $value, $secondBasePath, $path . "/" . $value);
			} else {
				if (\file_exists($secondBasePath . $secondPath . "/" . $value)) {
					$same = \md5_file($basePath . $path . "/" . $value) === \md5_file($secondBasePath . $secondPath . "/" . $value);
				} else {
					$same = false;
				}
			}
		}

		return $same;
	}
}
