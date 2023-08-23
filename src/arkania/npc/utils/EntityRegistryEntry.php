<?php

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
     * @param array $array
     * @return static
     * @throws InvalidStateException
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
     * @param CompoundTag $entry
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
     * @param string $identifier
     * @param string $behaviorId
     * @param int|null $runtimeId
     * @param bool $hasSpawnEgg
     * @param bool $isSummonable
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

    /**
     * @return string
     */
    public function getIdentifier() : string { return $this->identifier; }

    /**
     * @return string
     */
    public function getBehaviorId() : string { return $this->behaviorId; }

    /**
     * @return int|null
     */
    public function getRuntimeId() : ?int { return $this->runtimeId; }

    /**
     * @return bool
     */
    public function hasSpawnEgg() : bool { return $this->hasSpawnEgg; }

    /**
     * @return bool
     */
    public function isSummonable() : bool { return $this->isSummonable; }

    /**
     * @param CompoundTag $entry
     * @return void
     */
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