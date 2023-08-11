<?php
declare(strict_types=1);

namespace arkania\vote;

use pocketmine\item\Item;

abstract class BaseVoteReward {

    /** @var Item[] */
    protected array $items = [];

    /** @var string[] */
    protected array $commands = [];

    /** @var bool[] */
    protected array $xp = [];

    private int $money;

    public function addItem(Item $item) : self {
        $this->items[] = $item;
        return $this;
    }

    /*
     * 0 -> Player
     * 1 -> Console
     */
    public function addCommand(int $type, string $command) : self {
        $this->commands[$type] = $command;
        return $this;
    }

    public function addXp(int $xp, bool $level = false) : self {
        $this->xp[$xp] = $level;
        return $this;
    }

    public function addMoney(int $money): self {
        $this->money = $money;
        return $this;
    }

    abstract public function giveReward() : void;

}