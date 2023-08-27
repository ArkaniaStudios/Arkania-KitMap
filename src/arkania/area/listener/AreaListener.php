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

namespace arkania\area\listener;

use arkania\area\AreaManager;
use arkania\player\CustomPlayer;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\math\Vector3;

class AreaListener implements Listener {

    /**
     * @param Vector3 $pos
     * @param array $areaName
     * @return bool
     */
    public function canModifyArea(Vector3 $pos, array $areaName) : bool {
        $minXSpawn = $areaName['position']['min_x'];
        $maxXSpawn = $areaName['position']['max_x'];
        $minZSpawn = $areaName['position']['min_z'];
        $maxZSpawn = $areaName['position']['max_z'];
        return ($pos->getX() <= $maxXSpawn && $pos->getX() >= $minXSpawn) && ($pos->getZ() <= $maxZSpawn && $pos->getZ() >= $minZSpawn);

    }

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBlockBreak(BlockBreakEvent $event) : void {
        $block = $event->getBlock()->getPosition();
        /** @var CustomPlayer $player */
        $player = $event->getPlayer();
        $allArea = AreaManager::getInstance()->getAllArea();
        foreach ($allArea as $area) {
            if($this->canModifyArea($block, $area) && $area['param']['canBreakBlock'] === false) {
                if(!AreaManager::getInstance()->isAdminMode($player)) {
                    $event->cancel();
                }
            }
        }
    }

    /***
     * @param BlockPlaceEvent $event
     * @return void
     */
    public function onBlockPlace(BlockPlaceEvent $event) : void {
        $block = $event->getBlockAgainst()->getPosition();
        /** @var CustomPlayer $player */
        $player = $event->getPlayer();
        $allArea = AreaManager::getInstance()->getAllArea();
        foreach ($allArea as $area) {
            if($this->canModifyArea($block, $area) && $area['param']['canPlaceBlock'] === false) {
                if(!AreaManager::getInstance()->isAdminMode($player)) {
                    $event->cancel();
                }
            }
        }
    }

    /**
     * @param InventoryTransactionEvent $event
     * @return void
     */
    public function onInventoryTransaction(InventoryTransactionEvent $event) : void {
        /** @var CustomPlayer $playerName */
        $playerName = $event->getTransaction()->getSource();
        $player = $playerName->getPosition();
        $allArea = AreaManager::getInstance()->getAllArea();
        foreach ($allArea as $area) {
            if($this->canModifyArea($player, $area) && $area['param']['canPickUpItem'] === false) {
                if(!AreaManager::getInstance()->isAdminMode($playerName)) {
                    $event->cancel();
                }
            }
        }
    }

    /**
     * @param PlayerDropItemEvent $event
     * @return void
     */
    public function onPlayerDropItem(PlayerDropItemEvent $event) : void {
        /** @var CustomPlayer $playerName */
        $playerName = $event->getPlayer();
        $player = $playerName->getPosition();
        $allArea = AreaManager::getInstance()->getAllArea();
        foreach ($allArea as $area) {
            if($this->canModifyArea($player, $area) && $area['param']['canDropItem'] === false) {
                if(!AreaManager::getInstance()->isAdminMode($playerName)) {
                    $event->cancel();
                }
            }
        }
    }

    /**
     * @param CommandEvent $event
     * @return void
     */
    public function onCommand(CommandEvent $event) : void {
        $playerName = $event->getSender();
        if($playerName instanceof CustomPlayer) {
            $player = $playerName->getPosition();
            $allArea = AreaManager::getInstance()->getAllArea();
            foreach ($allArea as $area) {
                if($this->canModifyArea($player, $area) && $area['param']['canUseCommand'] === false) {
                    if(!AreaManager::getInstance()->isAdminMode($playerName)) {
                        if(!($event->getCommand() === 'spawn' || $event->getCommand() === 'area admin' || $event->getCommand() === 'lobby' || $event->getCommand() === 'area param')) {
                            $event->cancel();
                        }
                    }
                }
            }
        }
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onEntityDamage(EntityDamageEvent $event) : void {
        /** @var CustomPlayer $playerName */
        $playerName = $event->getEntity();
        $player = $playerName->getPosition();
        $allArea = AreaManager::getInstance()->getAllArea();
        if($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK) {
            foreach ($allArea as $area) {
                if($this->canModifyArea($player, $area) && $area['param']['pvp'] === false) {
                    $event->cancel();
                }
            }
        } else {
            foreach ($allArea as $area) {
                if($this->canModifyArea($player, $area) && $area['param']['canApplyDamage'] === false) {
                    $event->cancel();
                }
            }
        }
    }

}