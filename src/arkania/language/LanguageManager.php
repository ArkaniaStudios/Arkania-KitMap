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
use arkania\player\CustomPlayer;
use arkania\query\async\QueryAsyncTask;
use arkania\query\Query;
use pocketmine\lang\LanguageNotFoundException;
use pocketmine\utils\SingletonTrait;

class LanguageManager {
	use SingletonTrait;

	/** @var Language[] */
	private array $languages = [];

	/** @var string[] */
	private static array $player_lang = [];

	public function __construct() {
		Query::query(
			Main::getInstance(),
			new QueryAsyncTask(
				/** @phpstan-return (string|mixed)[] */
				static function () : array {
					$database = Query::getDataBase();
					$database->query("CREATE TABLE IF NOT EXISTS player_language (player_name VARCHAR(16) PRIMARY KEY, language_code VARCHAR(3));");
					$query = $database->query("SELECT * FROM player_language");
					if (\is_bool($query)) {
						return [];
					}

					return $query->fetch_all();
				},
				static function (mixed $result) : void {
					foreach ($result as $row) {
						self::$player_lang[$row[0]] = $row[1];
					}
				}
			)
		);

		$this->languages['fra'] = new Language('fr_FR');
		$this->languages['eng'] = new Language('en_EN');
	}

	public function setPlayerLanguage(CustomPlayer $player, string $language) : void {
		if (isset($this->languages[$language])) {
			$name = $player->getName();
			self::$player_lang[$name] = $language;
			Query::query(
				Main::getInstance(),
				new QueryAsyncTask(
					static function () use ($name, $language) : void {
						$database = Query::getDataBase();
						$query = $database->query("SELECT * FROM player_language WHERE player_name='$name';");
						if (\is_bool($query)) {
							return;
						}
						if ($query->num_rows <= 0) {
							$database->query("INSERT INTO player_language (player_name, language_code) VALUES ('$name', '$language');");
						} else {
							$database->query("UPDATE player_language SET language_code='$language' WHERE player_name='$name'");
						}
					},
					null,
					false
				)
			);

			return;
		}
		throw new LanguageNotFoundException($language);
	}

	public function getPlayerLanguage(CustomPlayer $player) : Language {
		if (!isset(self::$player_lang[$player->getName()]) || !isset($this->languages[self::$player_lang[$player->getName()]])) {
			$this->setPlayerLanguage($player, 'fra');

			return $this->languages['fra'];
		}

		return $this->languages[self::$player_lang[$player->getName()]];
	}

	public function getLanguage(string $language) : Language {
		if (isset($this->languages[$language])) {
			return $this->languages[$language];
		}
		throw new LanguageNotFoundException($language);
	}
}
