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

/**
 *     _      ____    _  __     _      _   _   ___      _             __     __  ____
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \            \ \   / / |___ \
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____   \ \ / /    __) |
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____|   \ V /    / __/
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            \_/    |_____|
 *
 * @author: Julien
 * @link: https://github.com/ArkaniaStudios
 */

namespace arkania\npc\utils;

use Couchbase\InvalidStateException;
use pocketmine\nbt\tag\CompoundTag;

class EntityRegistryEntry {

	public const TAG_BEHAVIOR_ID = "bid";
	public const TAG_HAS_SPAWN_EGG = "hasspawnegg";
	public const TAG_IDENTIFIER = "id";
	public const TAG_RUNTIME_ID = "rid";
	public const TAG_SUMMONABLE = "summonable";

	/**
	 * @throws InvalidStateException
	 * @return static
	 */
	public static function fromArray(array $array) : self {
		return new self(
			$array[ConfigKeys::ENTITY_IDENTIFIER] ?? throw new InvalidStateException(ConfigKeys::ENTITY_IDENTIFIER . " is required"),
			$array[ConfigKeys::ENTITY_BEHAVIOR_ID] ?? "",
			$array[ConfigKeys::ENTITY_RUNTIME_ID] ?? null,
			$array[ConfigKeys::ENTITY_HAS_SPAWNEGG] ?? false,
			$array[ConfigKeys::ENTITY_IS_SUMMONABLE] ?? false
		);
	}

	/**
	 * @return static
	 */
	public static function fromTag(CompoundTag $entry) : self {
		return new self(
			$entry->getString(self::TAG_IDENTIFIER),
			$entry->getString(self::TAG_BEHAVIOR_ID, ""),
			$entry->getTag(self::TAG_RUNTIME_ID)?->getValue(),
			$entry->getByte(self::TAG_HAS_SPAWN_EGG, 0) !== 0,
			$entry->getByte(self::TAG_SUMMONABLE, 0) !== 0
		);
	}

	/**
	 * @throws InvalidStateException
	 */
	public function __construct(
		private string $identifier,
		private string $behaviorId = "", // name
		private ?int $runtimeId = null,
		private bool $hasSpawnEgg = false,
		private bool $isSummonable = false
	) {
		if ($this->runtimeId !== null) {
			EntityRegistry::validateRuntimeId($this->runtimeId);
		}
		EntityRegistry::validateIdentifier($this->identifier);
	}

	public function getIdentifier() : string { return $this->identifier; }

	public function getBehaviorId() : string { return $this->behaviorId; }

	public function getRuntimeId() : ?int { return $this->runtimeId; }

	public function hasSpawnEgg() : bool { return $this->hasSpawnEgg; }

	public function isSummonable() : bool { return $this->isSummonable; }

	public function write(CompoundTag $entry) : void {
		$entry->setString(self::TAG_BEHAVIOR_ID, $this->behaviorId);
		$entry->setByte(self::TAG_HAS_SPAWN_EGG, $this->hasSpawnEgg ? 1 : 0);
		$entry->setString(self::TAG_IDENTIFIER, $this->identifier);
		if ($this->runtimeId !== null) {
			$entry->setInt(self::TAG_RUNTIME_ID, $this->runtimeId);
		}
		$entry->setByte(self::TAG_SUMMONABLE, $this->isSummonable ? 1 : 0);
	}

}
