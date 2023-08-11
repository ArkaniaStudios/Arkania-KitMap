<?php
declare(strict_types=1);

namespace arkania\economy\events;

use pocketmine\command\CommandSender;
use pocketmine\event\Event;

class PlayerDelMoneyEvent extends Event {

    public function __construct(
        private readonly CommandSender $staff,
        private readonly string $target,
        private readonly int $amount
    ) {}

    public function getPlayer() : CommandSender {
        return $this->staff;
    }

    public function getTarget() : string {
        return $this->target;
    }

    public function getAmount() : int {
        return $this->amount;
    }
}