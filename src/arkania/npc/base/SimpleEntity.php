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

namespace arkania\npc\base;

use arkania\commands\staff\NpcCommand;
use arkania\form\FormManager;
use arkania\npc\NpcDataIds;
use arkania\npc\NpcTrait;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;

abstract class SimpleEntity extends Living {
    use NpcTrait;

    public function __construct(Location $location, ?CompoundTag $nbt = null) {
        parent::__construct($location, $nbt);
        if(!is_null($nbt) && $nbt->getTag(NpcDataIds::ENTITY_NPC) !== null) {
            $this->restorNpcData($nbt);
            $this->setScale($this->getTaille());
        }
        $this->setNameTagAlwaysVisible();
    }

    /**
     * @return CompoundTag
     */
    public function saveNBT() : CompoundTag {
        $nbt = parent::saveNBT();
        if($this->isNpc()) {
            $nbt = $this->saveNpcData($nbt);
        }
        return $nbt;
    }

    /**
     * @param EntityDamageEvent $source
     * @return void
     */
    public function attack(EntityDamageEvent $source) : void {
        if(!$this->isNpc()) {
            parent::attack($source);
        } else if($source instanceof EntityDamageByEntityEvent) {
            $player = $source->getDamager();
            if($player instanceof CustomPlayer) {
                if($player->hasPermission(Permissions::COMMAND_NPC) || $player->getServer()->isOp($player->getName())){
                    if(isset(NpcCommand::$npc[$player->getName()])) {
                        if(NpcCommand::$npc[$player->getName()] === 'disband') {
                            $this->flagForDespawn();
                            if(!$player->isSneaking()) {
                                $player->sendMessage('npc.delete.success');
                                unset(NpcCommand::$npc[$player->getName()]);
                            } else {
                                $player->sendMessage('npc.delete.success');
                            }
                        } else if(NpcCommand::$npc[$player->getName()] === 'rotate') {
                            FormManager::getInstance()->sendNpcChangePositionForm($player, $this);
                            unset(NpcCommand::$npc[$player->getName()]);
                        } else if(NpcCommand::$npc[$player->getName()] === 'edit') {
                            FormManager::getInstance()->sendNpcWithItemForm($player, $this);
                            unset(NpcCommand::$npc[$player->getName()]);
                        }
                    }
                    if($player->getInventory()->getItemInHand()->getTypeId() === VanillaItems::RECORD_STRAD()->getTypeId()) {
                        FormManager::getInstance()->sendNpcWithItemForm($player, $this);
                    } else {
                        $this->executeCommand($player);
                    }
                } else {
                    $this->executeCommand($player);
                }
            }
        }
    }
}