<?php
declare(strict_types=1);

namespace arkania\events\player;

use pocketmine\event\Listener;

class PlayerAddMoneyEvent implements Listener {

    public function onPlayerAddMoney(\arkania\economy\events\PlayerAddMoneyEvent $event) : void {}

}