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

namespace arkania\permissions;

class Permissions {
	public const ARKANIA_REDEM = 'arkania.redem';
    public const ARKANIA_ENDERCHEST = 'arkania.enderchest';
    public const ARKANIA_CRAFT = 'arkania.craft';
	public const ARKANIA_LOGS = 'arkania.logs';
    public const ARKANIA_BROADCAST = 'arkania.broadcast';
    const ARKANIA_MAINTENANCE = 'arkania.maintenance';
    const ARKANIA_MAINTENANCE_BYPASS = 'arkania.maintenance.bypass';
    const ARKANIA_OP = 'arkania.op';
    const ARKANIA_DEOP = 'arkania.deop';
    const ARKANIA_ADDRANK = 'arkania.addrank';
    const ARKANIA_ADDMONEY = 'arkania.addmoney';
    const ARKANIA_DELMONEY = 'arkania.delmoney';
    const ARKANIA_DELRANk = 'arkania.delrank';
    const ARKANIA_DELETEUSER = 'arkania.deleteuser';
    const ARKANIA_SETRANK = 'arkania.setrank';
    const ARKANIA_TELEPORT = 'arkania.teleport';
}
