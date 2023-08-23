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

namespace arkania\npc;

use arkania\npc\utils\EntityRegistry;

class NpcManager {

    /** @var EntityRegistry|null */
    private static ?EntityRegistry $registry = null;

    /** @var string|null */
    private static ?string $fallbackEntity = null;

    /**
     * @return EntityRegistry
     */
    public static function getEntityRegistry() : EntityRegistry {
        return self::$registry ??= new EntityRegistry();
    }

    /**
     * @return string|null
     */
    public static function getFallbackEntity() : ?string {
        return self::$fallbackEntity;
    }

}