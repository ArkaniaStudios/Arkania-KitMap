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

use InvalidArgumentException;
use ZipArchive;

class FileSystem {

	public static function deleteRecursiveDir(string $dir) : void {
		if (!is_dir($dir)) {
			throw new InvalidArgumentException("$dir must be a directory");
		}
		if (!str_ends_with($dir, '/')) {
			$dir .= '/';
		}
		$files = glob($dir . '*', GLOB_MARK);
		if ($files === false) {
			return;
		}
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteRecursiveDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dir);
	}

	public static function unZipFile(string $path) : bool {
		$zipFilePath = $path . '.zip';
		$extractPath = $path;
		$zip = new ZipArchive();
		if ($zip->open($zipFilePath) === true) {
			$zip->extractTo($extractPath);
			$zip->close();
			return true;
		} else {
			return false;
		}
	}

}
