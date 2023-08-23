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

interface NpcDataIds {

    const ENTITY_COMMAND = 'arkania:npc.command';
    const ENTITY_SIZE = 'arkania:npc.size';
    const ENTITY_NAME = 'arkania:npc.name';
    const ENTITY_ID = 'arkania:npc.id';
    const ENTITY_SKIN_PATH = 'arkania:npc.skin.path';
    const ENTITY_INVENTAIRE = 'arkania:npc.inventaire';
    const ENTITY_PITCH = 'arkania:npc.pitch';
    const ENTITY_YAW = 'arkania:npc.yaw';
    const ENTITY_NPC = 'arkania:npc.isNpc';

}