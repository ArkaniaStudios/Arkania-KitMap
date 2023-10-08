<?php
declare(strict_types=1);

namespace arkania\staffmode;

use arkania\factions\FactionArgumentInvalidException;
use arkania\form\FormManager;
use arkania\player\CustomPlayer;
use arkania\ranks\RanksManager;
use arkania\utils\Utils;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\VanillaItems;
use pocketmine\Server;

class StaffModeListener implements Listener {

    /**
     * @param PlayerDropItemEvent $event
     * @return void
     */
    public function onCustomPlayerDropItem(PlayerDropItemEvent $event): void {
        $CustomPlayer = $event->getPlayer();

        if (StaffMode::getInstance()->isInStaffMode($CustomPlayer))
            $event->cancel();
    }

    /**
     * @param InventoryTransactionEvent $event
     * @return void
     */
    public function onItemTransaction(InventoryTransactionEvent $event): void {
        $CustomPlayer = $event->getTransaction()->getSource();
        if (StaffMode::getInstance()->isInStaffMode($CustomPlayer))
            $event->cancel();
    }

    /**
     * @param EntityItemPickupEvent $event
     * @return void
     */
    public function onEntityItemPickup(EntityItemPickupEvent $event): void {
        $CustomPlayer = $event->getEntity();

        if ($CustomPlayer instanceof CustomPlayer)
            if (StaffMode::getInstance()->isInStaffMode($CustomPlayer))
                $event->cancel();
    }

    /**
     * @param PlayerExhaustEvent $event
     * @return void
     */
    public function onCustomPlayerExhaust(PlayerExhaustEvent $event): void {
        $CustomPlayer = $event->getPlayer();

        if ($CustomPlayer instanceof CustomPlayer)

            if (StaffMode::getInstance()->isInStaffMode($CustomPlayer))
                $event->cancel();
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onEntityDamage(EntityDamageEvent $event): void {
        $CustomPlayer = $event->getEntity();

        if ($CustomPlayer instanceof CustomPlayer)
            if (StaffMode::getInstance()->isInStaffMode($CustomPlayer))
                $event->cancel();
    }

    /**
     * @param PlayerItemUseEvent $event
     * @return void
     * @throws FactionArgumentInvalidException
     */
    public function onCustomPlayerItemUse(PlayerItemUseEvent $event): void {
        $CustomPlayer = $event->getPlayer();
        $item = $event->getItem();

        if (!$CustomPlayer instanceof CustomPlayer) return;

        if (StaffMode::getInstance()->isInStaffMode($CustomPlayer)) {
            if ($item->getTypeId() === 351) {
                if (StaffMode::getInstance()->isInVanish($CustomPlayer)) {
                    StaffMode::getInstance()->removeVanish($CustomPlayer);
                }else{
                    StaffMode::getInstance()->setVanish($CustomPlayer);
                }
            }
            if ($item->getTypeId() === VanillaItems::COMPASS()->getTypeId()){
                $onlineCustomPlayer = Server::getInstance()->getOnlinePlayers();
                if (count($onlineCustomPlayer) <= 1){
                    $CustomPlayer->sendMessage(Utils::getPrefix() . "§cIl n'y a actuellement personne sur le serveur.");
                    return;
                }
                $random = $onlineCustomPlayer[array_rand($onlineCustomPlayer)];
                while($random === $CustomPlayer)
                    $random = $onlineCustomPlayer[array_rand($onlineCustomPlayer)];
                if ($random instanceof CustomPlayer){
                    $CustomPlayer->teleport($random->getLocation());
                    $CustomPlayer->sendMessage(Utils::getPrefix() . "§aVous avez été téléporté à " . $random->getRankFullFormat() . "§a.");
                }
            }
        }
    }

    /**
     * @param EntityDamageByEntityEvent $event
     * @return void
     * @throws FactionArgumentInvalidException
     */
    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event): void {
        $CustomPlayer = $event->getDamager();
        $target = $event->getEntity();
        if ($CustomPlayer instanceof CustomPlayer && $target instanceof CustomPlayer) {
            if (StaffMode::getInstance()->isInStaffMode($CustomPlayer)) {
                $event->cancel();
                $item = $CustomPlayer->getInventory()->getItemInHand()->getTypeId();
                if ($item === VanillaBlocks::ICE()->asItem()->getTypeId()) {
                    if (StaffMode::getInstance()->isFreeze($target)) {
                        StaffMode::getInstance()->setFreeze($target, false);
                        $target->sendMessage(Utils::getPrefix() . "§aVous n'êtes plus gelé !");
                        RanksManager::getInstance()->updateNameTag($target->getRank(), $target);
                        $CustomPlayer->sendMessage(Utils::getPrefix() . "§aVous avez dégelé " . $target->getRankFullFormat() . "§a.");
                    } else {
                        StaffMode::getInstance()->setFreeze($target);
                        $target->setNameTag("[§bFREEZE§f] " . $target->getName());
                        $target->sendMessage(Utils::getPrefix() . "§cVous avez été gelé par " . $CustomPlayer->getRankFullFormat() . "§c. Merci de suivre les indications qui vont vous êtes données.");
                        $target->sendTitle("§c§lFREEZE", "§r§cMerci de regarder votre chat !", 100, 100, 100);
                        $CustomPlayer->sendMessage(Utils::getPrefix() . "§aVous avez bien gelé " . $target->getRankFullFormat() . "§a.");
                    }
                }
            }
        }
    }
    
}