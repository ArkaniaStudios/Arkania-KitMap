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

interface ConfigKeys {

    public const FALLBACK_ENTITY = "fallback_entity";
    public const ENTITY_IDENTIFIER = "identifier";
    public const ENTITY_BEHAVIOR_ID = "behavior_id";
    public const ENTITY_RUNTIME_ID = "runtime_id";
    public const ENTITY_HAS_SPAWNEGG = "has_spawnegg";
    public const ENTITY_IS_SUMMONABLE = "is_summonable";

}