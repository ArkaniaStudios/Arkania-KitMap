<?php
declare(strict_types=1);

namespace arkania\factions\events;

use arkania\factions\ClaimManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\block\tile\Container;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\world\Position;

class FactionListener implements Listener {

    private function canEditClaim(CustomPlayer $player, Position $position) : bool {
        if (ClaimManager::isClaimed($position)) {
            $factionName = ClaimManager::getFactionName();
            if ($player->getFaction()?->getName() === $factionName || $player->isFactionAdmin()) {
                return true;
            }
        }else{
            return true;
        }
        $player->sendMessage(CustomTranslationFactory::arkania_faction_chunk_already_claimed($factionName));
        return false;
    }

    public function onBlockBreak(BlockBreakEvent $event) : void {
        $player = $event->getPlayer();
        if (!$player instanceof CustomPlayer) return;
        if (!$this->canEditClaim($player, $event->getBlock()->getPosition())) {
            $event->cancel();
        }
    }


    public function onBlockPlace(BlockPlaceEvent $event) : void {
        $player = $event->getPlayer();
        if (!$player instanceof CustomPlayer) return;
        if (!$this->canEditClaim($player, $event->getBlockAgainst()->getPosition())) {
            $event->cancel();
        }
    }

    public function onPlayerInteract(PlayerInteractEvent $event) : void {
        $player = $event->getPlayer();
        if (!$player instanceof CustomPlayer) return;
        $block = $event->getBlock();
        if ($block instanceof Container) {
            if (!$this->canEditClaim($player, $event->getBlock()->getPosition())) {
                $event->cancel();
            }
        }
    }

    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event) : void {
        $damager = $event->getDamager();
        $entity = $event->getEntity();
        if ($damager instanceof CustomPlayer && $entity instanceof CustomPlayer) {
            $damagerFaction = $damager->getFaction();
            $entityFaction = $entity->getFaction();

            if ($damagerFaction === null || $entityFaction === null) {
                return;
            }

            if ($damagerFaction->getName() === $entityFaction->getName()) {
                $event->cancel();
            }

            if ($damagerFaction->getAllyManager()->isAlly($entityFaction)) {
                $event->cancel();
            }

        }
    }
}