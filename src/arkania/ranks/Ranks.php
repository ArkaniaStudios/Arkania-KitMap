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

namespace arkania\ranks;

use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use arkania\ranks\elements\RanksFormatInfo;
use arkania\ranks\elements\RanksPermissions;
use JsonException;
use JsonSerializable;
use pocketmine\utils\Config;

class Ranks implements JsonSerializable {

	private string $rankName;

	/** @var ?RanksFormatInfo */
	private ?RanksFormatInfo $format;

	/** @var ?RanksFormatInfo */
	private ?RanksFormatInfo $nametag;

	private RanksPermissions|null $permissions;

	/** @var ?string */
	private ?string $color;

	/** @var ?bool */
	private ?bool $default;

	public function __construct(
		string $rankName,
		?RanksFormatInfo $format = null,
		?RanksFormatInfo $nametag = null,
		?RanksPermissions $permissions = null,
		?string $color = null,
		bool $default = false
	) {
		if (!file_exists(Main::getInstance()->getDataFolder() . 'ranks/')){
			mkdir(Main::getInstance()->getDataFolder() . 'ranks/');
		}
		$this->rankName = $rankName;
		$this->format = $format;
		$this->nametag = $nametag;
		$this->permissions = $permissions;
		$this->color = $color;
		$this->default = $default;
	}

	public function getName() : string {
		return $this->rankName;
	}

	public function getRankDataPath() : Config {
		return Path::config('ranks/' . $this->getName(), PathTypeIds::JSON());
	}

	public function getRankFormatInfo() : RanksFormatInfo {
		return $this->format;
	}

	public function getRankNametagFormatInfo() : RanksFormatInfo {
		return $this->nametag;
	}

	public function getRanksPermissions() : ?RanksPermissions {
		return $this->permissions;
	}

	public function getColor() : string {
		return $this->color;
	}

	public function isDefault() : bool {
		return $this->default;
	}

	/**
	 * @throws JsonException
	 */
	public function create() : bool {
		$config = Path::config('ranks/' . $this->getName(), PathTypeIds::JSON());
		if (empty($config->getAll())) {
			$config->setAll($this->jsonSerialize());
			$config->save();
			return true;
		}
		return false;
	}

	public function jsonSerialize() : array {
		return [
			"rankName" => $this->rankName,
			"format" => $this->format->getFormat(),
			"nametag" => $this->nametag->getFormat(),
			"permissions" => $this->permissions ?? [],
			"color" => $this->color,
			"default" => $this->default
		];
	}
}
