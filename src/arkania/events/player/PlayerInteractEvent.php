<?php
declare(strict_types=1);

namespace arkania\events\player;

use arkania\items\pvp\Soupe;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;

class PlayerInteractEvent implements Listener {

    public function onPlayerInteract(\pocketmine\event\player\PlayerInteractEvent $event) : void {
        $this->useSoupe($event);
    }

    public function onPlayerItemUse(PlayerItemUseEvent $event) : void {
        $this->useSoupe($event);
    }

    /**
     * @param PlayerItemUseEvent $event
     * @return void
     */
    private function useSoupe(PlayerItemUseEvent|\pocketmine\event\player\PlayerInteractEvent $event): void {
        $player = $event->getPlayer();
        $itemHand = $player->getInventory()->getItemInHand();
        if ($itemHand instanceof Soupe) {
            if ($player->getHealth() < $player->getMaxHealth()) {
                $player->setHealth($player->getHealth() + 2);
                $player->getInventory()->setItemInHand($itemHand->setCount($itemHand->getCount() - 1));
                $player->sendPopup('§a+1❤');
            }
        }
    }

}