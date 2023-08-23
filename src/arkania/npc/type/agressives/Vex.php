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

namespace arkania\npc\type\agressives;

use arkania\npc\base\SimpleEntity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Vex extends SimpleEntity {


    /**
     * @return EntitySizeInfo
     */
    protected function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo(0.8, 0.4);
    }

    /**
     * @return string
     */
    public static function getNetworkTypeId(): string {
        return EntityIds::VEX;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return 'vex';
    }
}