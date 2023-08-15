<?php
declare(strict_types=1);

namespace arkania\game\events;

use arkania\player\CustomPlayer;
use pocketmine\event\Event;

class MoneyZoneEvent extends Event {

    private CustomPlayer $player;
    private int $amount;
    private int $time;

    public function __construct(
        CustomPlayer $player,
        int $amount,
        int $time
    ) {
        $this->player = $player;
        $this->amount = $amount;
        $this->time = $time;
    }

    public function getPlayer() : CustomPlayer {
        return $this->player;
    }

    public function getAmount() : int {
        return $this->amount;
    }

    public function getTime() : int {
        return $this->time;
    }

    public function setAmount(int $amount) : void {
        $this->amount = $amount;
    }

    public function setTime(int $time) : void {
        $this->time = $time;
    }

}