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

namespace arkania\npc\base;

use arkania\commands\staff\NpcCommand;
use arkania\form\FormManager;
use arkania\npc\NpcDataIds;
use arkania\npc\NpcTrait;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;

class CustomEntity extends Human {
	use NpcTrait;
	public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null) {
		parent::__construct($location, $skin, $nbt);
		if (!is_null($nbt) && $nbt->getTag(NpcDataIds::ENTITY_NPC) !== null){
			$this->restorNpcData($nbt);
			$this->setScale($this->getTaille());
		}
		$this->setNameTagAlwaysVisible();
	}

	public function saveNBT() : CompoundTag {
		$nbt = parent::saveNBT();
		if ($this->isNpc()){
			$nbt = $this->saveNpcData($nbt);
		}
		return $nbt;
	}

	public function attack(EntityDamageEvent $source) : void {
		if (!$this->isNpc()){
			parent::attack($source);
		}elseif($source instanceof EntityDamageByEntityEvent){
			$player = $source->getDamager();
			if ($player instanceof CustomPlayer) {
				if($player->hasPermission(Permissions::COMMAND_NPC) || $player->getServer()->isOp($player->getName())){
					if (isset(NpcCommand::$npc[$player->getName()])) {
						if (NpcCommand::$npc[$player->getName()] === 'disband'){
							$this->flagForDespawn();
							if (!$player->isSneaking()){
								$player->sendMessage('npc.delete.success');
								unset(NpcCommand::$npc[$player->getName()]);
							}else{
								$player->sendMessage('npc.delete.success');
							}
						}elseif(NpcCommand::$npc[$player->getName()] === 'rotate'){
							FormManager::getInstance()->sendNpcChangePositionForm($player, $this);
							unset(NpcCommand::$npc[$player->getName()]);
						}elseif(NpcCommand::$npc[$player->getName()] === 'edit'){
							FormManager::getInstance()->sendNpcWithItemForm($player, $this);
							unset(NpcCommand::$npc[$player->getName()]);
						}
					}
					if ($player->getInventory()->getItemInHand()->getTypeId() === VanillaItems::RECORD_STRAD()->getTypeId()){
						FormManager::getInstance()->sendNpcWithItemForm($player, $this);
					}else {
						$this->executeCommand($player);
					}
				}else{
					$this->executeCommand($player);
				}
			}
		}
	}
}
