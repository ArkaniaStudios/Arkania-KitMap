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

use arkania\path\Path;
use arkania\path\PathTypeIds;
use JsonException;

class PlayerCreateData implements \JsonSerializable {

	private string $name;

	public function __construct(
		string $name,
	) {
		$this->name = $name;
	}

	/**
	 * @throws JsonException
	 */
	public function create() : bool {
		$config = Path::config('players/' . $this->name, PathTypeIds::JSON());
		if (empty($config->getAll())) {
			$config->setAll($this->jsonSerialize());
			$config->save();
			return true;
		}
		return false;
	}

	/**
	 * @return string[]
	 */
	public function jsonSerialize() : array {
		return [
			'name' => $this->name,
			'rank' => 'Joueur',
			'rankup' => [
				'rank' => 'Cooper',
				'level' => 1,
				'color' => '§6'
			],
			'permissions' => [],
			'kills' => 0,
			'deaths' => 0,
			'ban' => 0,
			'mute' => 0,
			'kick' => 0,
			'warn' => 0
		];
	}
}
