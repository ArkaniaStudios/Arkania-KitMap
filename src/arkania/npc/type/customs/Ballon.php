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

namespace arkania\npc\type\customs;

use arkania\npc\base\SimpleEntity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class Ballon extends SimpleEntity {


    /**
     * @return EntitySizeInfo
     */
    protected function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo(0.4, 0.5);
    }

    /**
     * @return string
     */
    public static function getNetworkTypeId(): string {
        return EntityIds::BALLOON;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return 'balloon';
    }

    /**
     * @param Player $player
     * @return void
     */
    public function onCollideWithPlayer(Player $player) : void {
        $this->knockBack($this->getPosition()->getX() - $player->getPosition()->getX(), $this->getPosition()->getZ() - $player->getPosition()->getZ(), 0.3);
        parent::onCollideWithPlayer($player);
    }

}