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

namespace arkania\language;

use arkania\Main;
use pocketmine\lang\LanguageNotFoundException;
use pocketmine\utils\Utils;
use Symfony\Component\Filesystem\Path;

class Language extends \pocketmine\lang\Language {
	public function __construct(
		string $lang,
		?string $path = null
	) {
		$path = $path ?? Main::getInstance()->getDataFolder() . 'languages';
		$this->lang = self::loadLang($path, $lang);
		$this->langName = $lang;
	}

	public function getName() : string {
		return $this->get(CustomTranslationKeys::ARKANIA_LANGUAGE_NAME);
	}

	/**
	 * @return string[]
	 */
	protected static function loadLang(string $path, string $languageCode) : array {
		$file = Path::join($path, $languageCode . '.lang');
		if (\file_exists($file)) {
			$strings = \array_map('stripcslashes', Utils::assumeNotFalse(\parse_ini_file($file, false, \INI_SCANNER_RAW)));
			if (\count($strings) > 0) {
				return $strings;
			}
		}
		throw new LanguageNotFoundException('Language "' . $languageCode . '" not found');
	}
}
